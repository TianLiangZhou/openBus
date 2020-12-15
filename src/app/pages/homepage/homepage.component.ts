import {Component, HostListener, OnDestroy, OnInit} from '@angular/core';
import {AmapService} from "../../shared/services/amap.service";
import {forkJoin} from "rxjs";
import {GeolocationPosition, InfoliteResponse, LineResponse, PoiSearchResponse, TipList} from "../../shared/data/amap";
import {Router} from "@angular/router";

@Component({
  selector: 'app-homepage',
  templateUrl: './homepage.component.html',
  styleUrls: ['./homepage.component.sass']
})
export class HomepageComponent implements OnInit {

  directors: { name: string, alias: string, icon: string, selected: boolean, submit: string }[] = [
    {name: "公交", alias: "bus", icon: "icon-bus", selected: true, submit: "坐公交"},
    {name: "驾车", alias: "car", icon: "icon-car", selected: false, submit: "驾车去"},
    {name: "步行", alias: "walk", icon: "icon-walk", selected: false, submit: "走路去"},
  ];

  submit = "坐公交";

  locationFromActive = false;
  locationToActive = false;

  locationFromValue = "";
  locationToValue = "";

  private map: any;

  loading = true;

  stations = [];

  nearbyStations = [];

  isFixedToolbar: boolean = false;

  constructor(
    private amapService: AmapService,
    private router: Router
  ) {
  }

  ngOnInit(): void {
    this.loadLocation();
  }

  loadLocation() {
    const _this = this;
    _this.loadMap();
    AMap.plugin('AMap.Geolocation', function () {
      const geolocation = new AMap.Geolocation({
        enableHighAccuracy: true,// 是否使用高精度定位，默认:true
        timeout: 5000,           // 超过10秒后停止定位，默认：5s
        maximumAge: 60000,
        position: 'RB',           // 定位按钮的停靠位置
        buttonOffset: new AMap.Pixel(10, 20), // 定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
        zoomToAccuracy: true, // 定位成功后是否自动调整地图视野到定位点
        showMarker: true,
        showButton: false,
        showCircle: false,
        markerOptions: {
          icon: new AMap.Icon({
            size: new AMap.Size(32, 32),
            image: 'assets/images/amap/location.webp',
            imageSize: new AMap.Size(32, 32),
          }),
          angle: 180,
          clickable: false,
        },
      });
      _this.amapService.geolocation = geolocation;
      _this.map.addControl(geolocation);
      geolocation.getCurrentPosition((status: string, result: any) => {
        if (status === 'complete') {
          _this.loading = false;
          _this.amapService.location = result;
          _this.loadData(result);
        } else {
          _this.onLocationError(result)
        }
      });
    });

  }

  private onLocationError(result: any) {
    console.log("定位失败:" + result.message)
  }

  director(alias: string) {
    this.directors.map((item) => {
      item.selected = item.alias == alias;
      if (item.selected) {
        this.submit = item.submit;
      }
    })
  }

  toInputBlur(inputElement: HTMLInputElement) {
    this.locationToActive = inputElement.value.trim() != "";
  }

  toInputFocus(inputElement: HTMLInputElement) {
    this.locationToActive = true;
  }

  fromInputFocus(inputElement: HTMLInputElement) {
    this.locationFromActive = true;
  }

  fromInputBlur(inputElement: HTMLInputElement) {
    this.locationFromActive = inputElement.value.trim() != "";
  }

  exchange() {
    if (this.locationFromValue.trim() == "" && this.locationToValue.trim() == "") {
      return;
    }
    const temp = this.locationToValue;
    this.locationToValue = this.locationFromValue;
    this.locationFromValue = temp;
  }

  lineFind() {

  }

  @HostListener("window:scroll", ['$event'])
  onScroll($event) {
    this.isFixedToolbar = window.scrollY >= 45;
    const scrollY = window.scrollY;
    let opacity
    if (scrollY >= 45) {
      if (scrollY <= 150) {
        opacity = scrollY / 150;
      } else {
        opacity = 1;
      }
    } else {
      opacity = 1;
    }
    (document.querySelector(".toolbar") as HTMLDivElement).style.opacity = opacity;
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) { // 拉到最底部
      console.log("scroll");
    }
  }


  lineDetail(line: any) {
    this.amapService.selectedLine = line;
    this.router.navigateByUrl("/line/" + line.lineid).then((redirect) => {
    })
  }

  searchClick(inputElement: HTMLInputElement) {
    this.router.navigate(["/search"], {
      queryParams: {
        "keywords": inputElement.value
      }
    }).then((redirect) => {
    })
  }

  private loadData(geolocation: GeolocationPosition) {
    const data = {
      category: 150700,
      latitude: geolocation.position.lat,
      longitude: geolocation.position.lng,
      pagenum: 1,
      pagesize: 4,
      query_type: "RQBXY",
      range: 2000,
      scenario: 2,
      sort_rule: 1
    };
    this.amapService.getPoi(data).subscribe((response) => {
      const nearStation = response.poi_list.shift();
      const stationLocation = [nearStation.longitude, nearStation.latitude];
      const xs =  nearStation.stations.xs.split('|')
      const xy =  nearStation.stations.ys.split('|')

      const stationPoints = [];
      for (let i = 0; i < xs.length; i++) {
        stationPoints.push([xs[i], xy[i]]);
      }
      const stationIdArray = nearStation.stations.businfo_ids.split('|')
      let stations = [];
      let lineRequest = [];
      stationIdArray.forEach((stationId, index) => {
        const distance = AMap.GeometryUtil.distance(stationLocation, stationPoints[index]);
        stations.push({
          "stationId": stationId,
          "name": nearStation.name.replace("(公交站)", ""),
          "distance": (Math.round(+nearStation.distance) + Math.round(distance)),
          "sort": distance,
          "direction": String.fromCharCode("A".charCodeAt(0) + index),
          "lines": [],
        });
        if (index == 0) {
          this.addStationMarker(stationPoints[index], nearStation.name.replace("(公交站)", ""))
        }
      });
      stations.sort((a, b) => (a.sort > b.sort) ? 1 : -1);

      stations.forEach((item) => {
        lineRequest.push(this.amapService.getStationLine(item.stationId));
      })
      let stationIndexes = {};
      forkJoin(lineRequest).subscribe((lines: LineResponse[]) => {
        let lineIdArray = [];
        let stationIdArray = [];
        lines.forEach((line, index) => {
          if (line.data.lines && line.data.lines.length > 0) {
            let lines = line.data.lines;
            lines.forEach((item, lineIndex) => {
              const names = item.name.match(/(.*?)\((.*?)\-\-(.*?)\)/);
              lines[lineIndex]["name"] = names[1];
              lines[lineIndex]["start_point"] = names[2];
              lines[lineIndex]["end_point"] = names[3];
              lines[lineIndex]["sub_text"] = "";
              lines[lineIndex]["now_real_trip"] = {
                "arrival": 0,
                "station_left": 0,
                "sub_text": "",
              };
              lines[lineIndex]["next_real_trip"] = {
                "arrival": 0,
                "station_left": 0,
                "sub_text": "",
              };
              lines[lineIndex]["sub_status"] = "100";
              stationIndexes[item.stationid + item.lineid] = [index, lineIndex];
              lineIdArray.push(item.lineid)
              stationIdArray.push(item.stationid)
            });
            stations[index]["lines"] = lines;
          }
        });

        this.amapService.getLineStation(lineIdArray.join(','), stationIdArray.join(',')).subscribe((realtimeResponse) => {
          if (!realtimeResponse.hasOwnProperty("code")) {
            this.stations = stations;
            return ;
          }
          if (realtimeResponse.code !== "1") {
            this.stations = stations;
            return ;
          }
          realtimeResponse.buses.forEach((item) => {
            const stationIndex = item.station + item.line;
            if (!stationIndexes.hasOwnProperty(stationIndex)) {
              return ;
            }
            const index = stationIndexes[stationIndex][0];
            const lineIndex = stationIndexes[stationIndex][1];
            stations[index]["lines"][lineIndex]["sub_text"] = item.sub_text;
            stations[index]["lines"][lineIndex]["status"] = item.status;
            stations[index]["lines"][lineIndex]["sub_status"] = item.sub_status;
            switch (item.status) {
              case "1":
                const trips = item.trip;
                if (trips.length < 1) {
                  stations[index]["lines"][lineIndex]["now_real_trip"]["sub_text"] = item.sub_text;
                  break;
                }

                const nowRealTrip = {
                  "arrival": +trips[0].arrival > 0 ? Math.ceil(+trips[0].arrival / 60) : 0,
                  "station_left": +trips[0].station_left,
                  "sub_text": trips[0].arrival == "0" ? "已到达" : "",
                };
                if (trips[0].arrival == "0" && trips[0].station_left == "0") {
                  nowRealTrip["sub_text"] = "车已到站";
                }
                if (+trips[0].arrival <= 90 && trips[0].station_left == "0") {
                  nowRealTrip["arrival"] = 0;
                  nowRealTrip["sub_text"] = "车即将进站";
                }
                if (+trips[0].arrival > 90 && trips[0].station_left == "0") {
                  nowRealTrip["station_left"] = 1;
                }
                stations[index]["lines"][lineIndex]["now_real_trip"] = nowRealTrip;
                if (trips.length > 1) {
                  stations[index]["lines"][lineIndex]["next_real_trip"] = {
                    "arrival": +trips[1].arrival > 0 ? Math.ceil(+trips[1].arrival / 60) : 0,
                    "station_left": +trips[1].station_left + 1,
                    "sub_text": "",
                  };
                }
                break;
              default:
                const subText = item.sub_text.split(",");
                stations[index]["lines"][lineIndex]["now_real_trip"]["sub_text"] = subText[0];
                if (subText.length > 1) {
                  stations[index]["lines"][lineIndex]["next_real_trip"]["sub_text"] = subText[1];
                }
            }
          });
          console.log(stations);
          this.stations = stations;
        });
      });
      const nearStations = [];
      response.poi_list.forEach((item) => {
        const otherXS =  item.stations.xs.split('|')
        const otherXY =  item.stations.ys.split('|')
        const otherStationPoints = [];
        for (let i = 0; i < otherXS.length; i++) {
          otherStationPoints.push([otherXS[i], otherXY[i]]);
        }
        const stationIdArray = item.stations.businfo_ids.split('|');
        const lineKeyNameArray = item.stations.businfo_line_keys.split('|');
        stationIdArray.forEach((stationId, index) => {
          const distance = AMap.GeometryUtil.distance(
            [item.longitude, item.latitude],
            otherStationPoints[index]
          );
          nearStations.push({
            "stationId": stationId,
            "name": item.name.replace("(公交站)", ""),
            "distance": (Math.round(+item.distance) + Math.round(distance)),
            "sort": distance,
            "direction": String.fromCharCode("A".charCodeAt(0) + index),
            "lines": [],
            "lines_name": lineKeyNameArray[index].replace(/;/g, '、'),
          });
          if (index == 0) {
            this.addStationMarker(otherStationPoints[index], item.name.replace("(公交站)", ""))
          }
        });
      });
      nearStations.sort((a, b) => (a.distance > b.distance) ? 1 : -1);
      this.map.setZoomAndCenter(15, geolocation.position, false, 500);
      this.nearbyStations = nearStations;
    });
  }

  private addStationMarker(position, name) {
    new AMap.Marker({
      position: position,
      icon: new AMap.Icon({
        size: new AMap.Size(29, 32),
        image: 'assets/images/amap/drive_bus_station.webp',
        imageSize: new AMap.Size(29, 32),
      }),
      anchor: 'bottom-center',
      zIndex: 15,
      label: {
        content: "<div style='border: none;color: #1A1B1C; font-weight: 500;'>"+ name +"</div>",
        direction:'bottom',
        offset: new AMap.Pixel(0, 3) //设置偏移量
      },
      map: this.map,
    })
  }

  private loadMap() {
    this.map = new AMap.Map('map', {
      // center: new AMap.LngLat(location.position.lng, location.position.lat),
      resizeEnable: false,
      dragEnable: false,
      rotateEnable: false,
      keyboardEnable: false,
      doubleClickZoom: false,
      scrollWheel: false,
      touchZoom: false,
      // viewMode:'3D'//使用3D视图
    });
  }
}
