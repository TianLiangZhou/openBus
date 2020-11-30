import {
  AfterViewInit,
  Component,
  ElementRef,
  OnInit,
  ViewChild
} from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {AmapService} from "../../shared/services/amap.service";
import {BuslineList, GeolocationPosition, LineDetailResponse, Station} from "../../shared/data/amap";
import {Subject} from "rxjs";

@Component({
  selector: 'app-line-detail',
  templateUrl: './line-detail.component.html',
  styleUrls: ['./line-detail.component.sass']
})
export class LineDetailComponent implements OnInit, AfterViewInit {

  @ViewChild('lineDetailStation', {static: true}) detailStation: ElementRef;

  private map: any;

  detail: BuslineList = null;

  private scrollElement;

  loading: boolean = true;

  private marker: any[] = [];

  private selectedMarker: any[] = [];

  private lastId = 0;

  private selectLocation = new Subject<any>();

  constructor(
    private route: ActivatedRoute,
    private amapService: AmapService,
  ) {
  }


  ngOnInit(): void {
    this.map = new AMap.Map('map', {});
    this.scrollElement = this.detailStation.nativeElement;
    this.selectLocation.subscribe((location) => {
      this.onSelectedLocation(location);
    });
    this.route.params.subscribe(p => {
      this.amapService.getLineDetail(p["id"]).subscribe((detail) => {
        this.loading = false;
        let lineDetail = detail.busline_list[detail.busline_count - 1];
        this.drawRoute(lineDetail)
        if (this.amapService.geolocation == null) {
          this.loadGeolocation(this.map);
        } else {
          this.map.add(
            new AMap.Marker({
              position: this.amapService.geolocation.position,
              icon: new AMap.Icon({
                size: new AMap.Size(32, 32),
                image: 'assets/images/amap/location.webp',
                imageSize: new AMap.Size(32, 32),
              }),
                angle: 180,
              clickable: false,
            })
          );
        }
        this.detail = lineDetail;
      });
    });
  }

  ngAfterViewInit(): void {
    // this.height = (window.innerHeight - 295);
    // this.scrollElement.style.height = this.height + "px";
  }

  selectedStation(index: number, station: Station, $event: Event) {
    this.detail.stations.map((item, i) => {
      item.selected = i == index;
    });
    const pos = station.xy_coords.split(";");
    this.selectLocation.next({index: index, position: pos});
    this.map.setZoomAndCenter(15, pos, false, 500);
  }

  private onSelectedLocation(location) {
    this.selectedMarker[this.lastId].hide();
    this.selectedMarker[location.index].show();
    if (this.lastId != 0 && this.lastId != this.detail.stations.length - 1) {
      this.marker[this.lastId].setIcon(
        new AMap.Icon({
          // 图标尺寸
          size: new AMap.Size(12, 12),
          // 图标的取图地址
          image: 'assets/images/amap/station_location.png',
          // 图标所用图片大小
          imageSize: new AMap.Size(12, 12),
        }),
      );
    }
    this.lastId = location.index;
    if (this.lastId != 0 && this.lastId != this.detail.stations.length - 1) {
      this.marker[location.index].setIcon(
        new AMap.Icon({
          // 图标尺寸
          size: new AMap.Size(12, 12),
          // 图标的取图地址
          image: 'assets/images/amap/station_location_point.png',
          // 图标所用图片大小
          imageSize: new AMap.Size(12, 12),
        })
      );
    }
  }

  private drawRoute (lineDetail: BuslineList) {
    const stations = lineDetail.stations;
    const stationCount = stations.length;
    new AMap.Marker({
      position: stations[0].xy_coords.split(';'),
      icon: new AMap.Icon({
        size: new AMap.Size(28, 28),
        image: 'assets/images/amap/bubble_start.webp',
        imageSize: new AMap.Size(28, 28),
      }),
      map: this.map,
      anchor: 'bottom-center',
      zIndex: 14,
    });
    new AMap.Marker({
      position: stations[stationCount - 1].xy_coords.split(';'),
      icon: new AMap.Icon({
        size: new AMap.Size(28, 28),
        image: 'assets/images/amap/bubble_end.webp',
        imageSize: new AMap.Size(28, 28),
      }),
      map: this.map,
      anchor: 'bottom-center',
      zIndex: 14,
    });
    const routeLines = [];
    const path = [];
    const selectedLine = this.amapService.selectedLine;
    let selectedIndex = 0;
    let pos = [];

    stations.forEach((item, index) => {
      const segment = item.xy_coords.split(';');
      item.selected = selectedLine == null ? index == 0 : selectedLine.stationid == item.station_id;
      if (item.selected) {
        selectedIndex = index;
        pos = segment;
      }
      this.marker.push(
        new AMap.Marker({
          position: segment,
          title: item.name,
          icon: new AMap.Icon({
            // 图标尺寸
            size: new AMap.Size(12, 12),
            // 图标的取图地址
            image: index == 0 || index == (stationCount - 1) ? 'assets/images/amap/station_location_point.png' :  'assets/images/amap/station_location.png',
            // 图标所用图片大小
            imageSize: new AMap.Size(12, 12),
          }),
          map: this.map,
          anchor: 'center',
          zooms: [index == 0 || index == (stationCount - 1) ? 2 : 15, 20],
          label: {
            content: "<div style='border: none;color: #1A1B1C; font-weight: 500;'>"+ item.name+"</div>",
            direction:'bottom',
            offset: new AMap.Pixel(0, 3) //设置偏移量
          },
          zIndex: 12,
        })
      );
      this.selectedMarker.push(
        new AMap.Marker({
          position: segment,
          icon: new AMap.Icon({
            size: new AMap.Size(29, 32),
            image: 'assets/images/amap/drive_bus_station.webp',
            imageSize: new AMap.Size(29, 32),
          }),
          anchor: 'bottom-center',
          zIndex: 15,
          map: this.map,
          visible: false,
        })
      );
    });
    this.selectLocation.next({
      index: selectedIndex,
      position: pos,
    });
    const xs = lineDetail.xs.split(',');
    const ys = lineDetail.ys.split(',');
    xs.forEach((value, index) => {
      path.push(new AMap.LngLat(value, ys[index]));
    });
    const line = new AMap.Polyline({
      path: path,
      isOutline: false,
      outlineColor: '#ffeeee',
      borderWeight: 2,
      strokeWeight: 5,
      strokeColor: '#0091ff',
      strokeOpacity: 1.0,
      lineJoin: 'round',
      strokeStyle: 'solid'
    });

    this.map.add(line);
    routeLines.push(line)
    if (location != null) {
      this.map.setZoomAndCenter(14, location, false, 500);
    } else {
      this.map.setZoom(14);
    }
    // 调整视野达到最佳显示区域
    // this.map.setFitView([startMarker, endMarker].concat(routeLines));
  }
  private loadGeolocation(map: any) {
    const _this = this;
    AMap.plugin('AMap.Geolocation', function () {
      const geolocation = new AMap.Geolocation({
        enableHighAccuracy: true,// 是否使用高精度定位，默认:true
        timeout: 5000,           // 超过10秒后停止定位，默认：5s
        position: 'RB',           // 定位按钮的停靠位置
        buttonOffset: new AMap.Pixel(10, 20), // 定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
        zoomToAccuracy: false, // 定位成功后是否自动调整地图视野到定位点
        panToLocation: true,
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
      map.addControl(geolocation);
      geolocation.getCurrentPosition((status: string, result: any) => {
        if (status !== 'complete') {
          return ;
        }
        _this.amapService.location = result;
      });
    });
  }
}
