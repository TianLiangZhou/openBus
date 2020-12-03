import { Injectable } from '@angular/core';
import {HttpClient, HttpParams} from "@angular/common/http";
import {Observable} from "rxjs";
import {
  GeolocationPosition,
  InfoliteResponse,
  LineDetailExResponse,
  LineDetailResponse,
  LineResponse,
  LineStationResponse, NearByLinesResponse, PoiSearchResponse
} from "../data/amap";

@Injectable({
  providedIn: 'root'
})
export class AmapService {
  private gateway = "https://aisle.amap.com";

  private secret = "59f783b90e9cb4aaa352b66da1a8d358";

  private commonData = {
    "appFrom": "alipay",
    "channel": "amap7a",
    "key": this.secret,
    "miniappid": "2018051660134749",
    'version': '2.13'
  };

  private commonHeaders = {
    "Referer": "https://2018051660134749.hybrid.alipay-eco.com/2018051660134749/0.2.2009282028.54/index.html#pages/realtimebus-index/realtimebus-index",
  };

  private _geolocation;

  private _selectedLine;

  private _location: GeolocationPosition;



  constructor(private http: HttpClient) {
  }

  getPoi(argument): Observable<InfoliteResponse>  {
    const url = this.gateway +'/ws/mapapi/poi/infolite';
    const data = {...argument, ...this.commonData};
    let body = new HttpParams({fromObject: data})
    return this.http.post<InfoliteResponse>(url, body, {
      headers: this.commonHeaders,
    })
  }


  getStationLine(stationId: string): Observable<LineResponse> {
    const url = this.gateway +'/ws/mapapi/realtimebus/search/lines';
    const data = {...this.commonData, ...{"station_id": stationId}};
    let body = new HttpParams({fromObject: data})
    return this.http.post<LineResponse>(url, body, {
      headers: this.commonHeaders,
    })
  }

  getRealtimeLine(line: string, station:  string): Observable<LineStationResponse> {
    const url = this.gateway + '/ws/mapapi/realtimebus/linestation';
    const data = {
      ...this.commonData,
      ...{"diu": "", "lines": line, "stations": station, "need_bus_status": "1", "uid": "2088102482090905"}
    };
    let body = new HttpParams({fromObject: data})
    return this.http.post<LineStationResponse>(url, body, {
      headers: this.commonHeaders,
    })
  }

  getLineDetail(lineId: string) : Observable<LineDetailResponse> {
    const url = this.gateway + '/ws/mapapi/poi/newbus';
    const data = {
      ...this.commonData,
      ...{"id": lineId}
    };
    let body = new HttpParams({fromObject: data})
    return this.http.post<LineDetailResponse>(url, body, {
      headers: this.commonHeaders,
    })
  }

  getLineDetailEx(lineId: string) :Observable<LineDetailExResponse> {
    const url = this.gateway + '/ws/mapapi/realtimebus/lines/ex/';
    const data = {
      ...this.commonData,
      ...{"lines": lineId, "div": "070101"}
    };
    let body = new HttpParams({fromObject: data})
    return this.http.post<LineDetailExResponse>(url, body, {
      headers: this.commonHeaders,
    })
  }

  getNearByLines(lat: string, lon: string): Observable<NearByLinesResponse> {
    const url = this.gateway + '/ws/mapapi/realtimebus/search/nearby_lines';
    const data = {
      ...this.commonData,
      ...{"lat": lat, "lon": lon}
    };
    let body = new HttpParams({fromObject: data})
    return this.http.post<NearByLinesResponse>(url, body, {
      headers: this.commonHeaders,
    })
  }


  getLocationByIp() {
    const url = this.gateway + '/v3/ip?key=' + this.secret;
    return this.http.get(url,{
      headers: this.commonHeaders,
    })
  }
  getLocationByAddress(address: string) {
    const url = this.gateway + '/v3/geocode/geo?key=' + this.secret + '&address=' + address;
    return this.http.get(url,{
      headers: this.commonHeaders,
    })
  }

  getPoiSearch(words: string, category: string = "999901%7C999907%7C999916%7C150700"): Observable<PoiSearchResponse> {
    const data = {...this.commonData, ...{"words": words, "adcode": "true", "category": category}};
    const url = this.gateway + '/ws/mapapi/poi/tipslite';
    return this.http.get<PoiSearchResponse>(url, {
      headers: this.commonHeaders,
      params: new HttpParams({fromObject: data})
    })
  }

  get selectedLine() {
    return this._selectedLine;
  }

  set selectedLine(value) {
    this._selectedLine = value;
  }
  get geolocation() {
    return this._geolocation;
  }

  set geolocation(value) {
    this._geolocation = value;
  }

  get location() {
    return this._location;
  }

  set location(value) {
    this._location = value;
  }
}
