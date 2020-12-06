import {
  AfterViewInit,
  Component,
  ElementRef,
  OnInit, QueryList,
  ViewChild, ViewChildren
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
  @ViewChildren('item') itemElements: QueryList<any>;
  private map: any;

  detail: BuslineList = {
    stations: []
  };

  private scrollElement: HTMLElement;

  loading: boolean = true;

  private marker: any[] = [];

  private selectedMarker: any[] = [];

  private startEndMarker: any[] = [];

  private polyline = null;

  private lastId = 0;

  private selectLocation = new Subject<any>();

  private stationCount = 0;

  private selectedIndex = null;

  constructor(
    private route: ActivatedRoute,
    private amapService: AmapService,
  ) {
  }


  ngOnInit(): void {
    this.map = new AMap.Map('map', {
      rotateEnable: false,
      center: this.amapService.location != null ? this.amapService.location.position : null,
      zoom: this.amapService.location != null ? 14 : null,
    });
    this.selectLocation.subscribe((location) => {
      this.onSelectedLocation(location);
    });
    this.route.params.subscribe(p => {
      this.loadGeolocation(this.map, p["id"]);
    });
  }

  ngAfterViewInit(): void {
    this.scrollElement = this.detailStation.nativeElement;
    this.scrollElement.style.height = (window.innerHeight - 300) + 'px';
    this.itemElements.changes.subscribe(_ => this.onItemElementsChanged());
  }

  exchange(lineId: string) {
    if (lineId.length < 1) {
      return ;
    }
    this.loading = true;
    this.selectedIndex = null;
    this.loadLineStation(this.amapService.location.position, lineId);
  }

  selectedStation(index: number, station: Station, $event: Event) {
    if (index == this.selectedIndex) {
      return ;
    }
    this.detail.stations.map((item, i) => {
      item.selected = i == index;
    });
    this.selectedIndex = index;
    const pos = station.xy_coords.split(";");
    this.selectLocation.next({index: index, position: pos});
    this.map.setZoomAndCenter(15, pos, false, 500);
    this.onItemElementsChanged();
  }

  private onItemElementsChanged() {
    const nativeElement = (this.itemElements.find((_, index) => index == this.selectedIndex) as ElementRef).nativeElement;;
    nativeElement.scrollIntoView({behavior: "smooth", block: "center", inline: "center"})
    // let top = nativeElement.offsetTop - 300 - (3 * nativeElement.clientHeight);
    // this.scrollElement.scroll({
    //   top: top,
    //   left: 0,
    //   behavior: "smooth",
    // })
  }

  private onSelectedLocation(location) {
    this.selectedMarker[this.lastId].hide();
    this.selectedMarker[location.index].show();
    if (this.lastId != 0 && this.lastId != this.stationCount - 1) {
      this.marker[this.lastId].setIcon(
        new AMap.Icon({
          // 图标尺寸
          size: new AMap.Size(12, 12),
          // 图标的取图地址
          image: 'assets/images/amap/station_location.png',
          // 图标所用图片大小
          imageSize: new AMap.Size(12, 12),
        })
      );
      this.marker[this.lastId].setOptions({
        zooms: [15, 20],
      });
    }
    this.lastId = location.index;
    if (location.index != 0 && location.index != this.stationCount - 1) {
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
      this.marker[location.index].setOptions({
        zooms: [2, 20],
      });
    }
  }

  private loadGeolocation(map: any, id: string) {
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
        _this.loadLineStation(result.position, id);
      });
    });
  }
  private loadLineStation(position: any, id: string) {
    this.amapService.getLineDetail(id).subscribe((detail) => {
      this.loading = false;
      let lineDetail = detail.busline_list[detail.busline_count - 1];
      this.stationCount = lineDetail.stations.length;
      this.drawRoute(lineDetail, position)
      this.detail = lineDetail;
    });
  }

  private drawRoute(lineDetail: BuslineList, position: any) {
    const stations = lineDetail.stations;
    this.startEndPoint(stations[0].xy_coords, stations[this.stationCount - 1].xy_coords);
    const path = [];
    const selectedLine = this.amapService.selectedLine;
    let pos = [];
    const distance = [];
    stations.forEach((item, index) => {
      const segment = item.xy_coords.split(';');
      item.selected = selectedLine == null ? false : selectedLine.stationid == item.station_id;
      if (item.selected) {
        this.selectedIndex = index;
        pos = segment;
      }
      const selected = index == 0 || index == (this.stationCount - 1) || item.selected;
      this.stationMarker(segment, item.name, selected, index);
      this.selectedStationMarker(segment, index);
    });
    this.hiddenResidueMarker();
    if (this.selectedIndex == null) {
      stations.forEach((item, index) => {
        const segment = item.xy_coords.split(';');
        const p1 = new AMap.LngLat(segment[0], segment[1]);
        const dis = Math.round(position.distance(p1));
        distance.push({d: dis, index: index});
      });
      distance.sort((a, b) => a.d - b.d);
      this.selectedIndex = distance[0].index;
      pos = stations[this.selectedIndex].xy_coords.split(';');
      stations[this.selectedIndex].selected = true;
    }
    console.log(stations);
    console.log(this.selectedIndex);
    this.selectLocation.next({
      index: this.selectedIndex,
      position: pos,
    });
    const xs = lineDetail.xs.split(',');
    const ys = lineDetail.ys.split(',');
    xs.forEach((value, index) => {
      path.push(new AMap.LngLat(value, ys[index]));
    });
    if (this.polyline == null) {
      this.polyline = new AMap.Polyline({
        path: path,
        isOutline: false,
        outlineColor: '#ffeeee',
        borderWeight: 2,
        strokeWeight: 6,
        strokeColor: '#0091ff',
        strokeOpacity: 1.0,
        lineJoin: 'round',
        strokeStyle: 'solid'
      });
      this.map.add(this.polyline);
    } else {
      this.polyline.setPath(path);
    }
    this.map.add(this.marker);
    this.map.setZoomAndCenter(14, pos, false, 500);
    // 调整视野达到最佳显示区域
    // this.map.setFitView(this.marker);
  }

  private hiddenResidueMarker() {
    if (this.marker.length > this.stationCount) {
      const len = this.marker.length - this.stationCount;
      for (let i = 0; i < len; i++) {
        this.marker[this.stationCount + i].hide();
      }
    }
  }

  private stationMarker(position: string[], name: string, selected: boolean, index: number) {
    if (this.marker.length - 1 < index) {
      this.marker.push(this.getMarker(position, name, selected));
      return ;
    }
    this.marker[index].setPosition(position);
    this.marker[index].setLabel(
      {
        content: "<div style='border: none;color: #1A1B1C; font-weight: 500;'>" + name + "</div>",
        direction: 'bottom',
        offset: new AMap.Pixel(0, 3) //设置偏移量
      },
    );
  }

  private selectedStationMarker(position, index) {
    if (this.selectedMarker.length - 1 < index) {
      this.selectedMarker.push(this.getStationMarker(position));
      return ;
    }
    this.selectedMarker[index].setPosition(position);
  }

  private startEndPoint(start, end) {
    const startPosition = start.split(';');
    const endPosition = end.split(';');
    const startIcon = new AMap.Icon({
      size: new AMap.Size(28, 28),
      image: 'assets/images/amap/bubble_start.webp',
      imageSize: new AMap.Size(28, 28),
    });
    const endIcon = new AMap.Icon({
      size: new AMap.Size(28, 28),
      image: 'assets/images/amap/bubble_end.webp',
      imageSize: new AMap.Size(28, 28),
    });

    if (this.startEndMarker.length < 1) {
      this.startEndMarker[0] = new AMap.Marker({
        position: startPosition,
        icon: startIcon,
        map: this.map,
        anchor: 'bottom-center',
        zIndex: 14,
      });
      this.startEndMarker[1] = new AMap.Marker({
        position: endPosition,
        icon: endIcon,
        map: this.map,
        anchor: 'bottom-center',
        zIndex: 14,
      });
      return ;
    }
    this.startEndMarker[0].setPosition(startPosition)
    this.startEndMarker[0].setIcon(startIcon);
    this.startEndMarker[1].setPosition(endPosition)
    this.startEndMarker[1].setIcon(endIcon);
  }

  private getMarker(segment, name, selected) {
    return new AMap.Marker({
      position: segment,
      title: name,
      icon: new AMap.Icon({
        // 图标尺寸
        size: new AMap.Size(12, 12),
        // 图标的取图地址
        image: selected ? 'assets/images/amap/station_location_point.png' : 'assets/images/amap/station_location.png',
        // 图标所用图片大小
        imageSize: new AMap.Size(12, 12),
      }),
      anchor: 'center',
      zooms: [selected ? 2 : 15, 20],
      label: {
        content: "<div style='border: none;color: #1A1B1C; font-weight: 500;'>" + name + "</div>",
        direction: 'bottom',
        offset: new AMap.Pixel(0, 3) //设置偏移量
      },
      zIndex: 12,
    });
  }

  private getStationMarker(segment) {
    return new AMap.Marker({
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
  }
}
