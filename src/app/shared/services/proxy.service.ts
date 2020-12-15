import {
  GeolocationPosition,
  InfoliteResponse, LineDetailExResponse,
  LineDetailResponse,
  LineResponse,
  LineStationResponse, NearByLinesResponse,
  PoiRealBus, PoiSearchResponse
} from "../data/amap";
import {environment} from "../../../environments/environment";
import {HttpClient, HttpParams} from "@angular/common/http";
import {Observable} from "rxjs";
import {Injectable} from "@angular/core";

@Injectable({
  providedIn: 'root'
})
export class ProxyService implements PoiRealBus{

  private gateway = environment.gateway;

  private _geolocation;

  private _selectedLine;

  private _location: GeolocationPosition;

  constructor(private http: HttpClient) {
  }

  getPoi(argument): Observable<InfoliteResponse>  {
    const url = this.gateway +'/amap/poi_info_lite';
    let body = new HttpParams({fromObject: argument})
    return this.http.post<InfoliteResponse>(url, body)
  }


  getStationLine(stationId: string): Observable<LineResponse> {
    const url = this.gateway +'/amap/station_line';
    let body = new HttpParams({fromObject: {"station_id": stationId}})
    return this.http.post<LineResponse>(url, body)
  }

  getLineStation(line: string, station:  string): Observable<LineStationResponse> {
    const url = this.gateway + '/amap/line_station';
    const data = {"diu": "", "lines": line, "stations": station, "need_bus_status": "1", "uid": "2088102482090905"}
    let body = new HttpParams({fromObject: data})
    return this.http.post<LineStationResponse>(url, body)
  }

  getLineDetail(lineId: string) : Observable<LineDetailResponse> {
    const url = this.gateway + '/amap/line';
    let body = new HttpParams({fromObject: {"id": lineId}})
    return this.http.post<LineDetailResponse>(url, body)
  }

  getLineDetailExtent(lineId: string) :Observable<LineDetailExResponse> {
    const url = this.gateway + '/amap/line_ex';
    let body = new HttpParams({fromObject: {"lines": lineId, "div": "070101"}})
    return this.http.post<LineDetailExResponse>(url, body)
  }

  getNearLine(lat: string, lon: string): Observable<NearByLinesResponse> {
    const url = this.gateway + '/amap/near_line';
    let body = new HttpParams({fromObject: {"lat": lat, "lon": lon}});
    return this.http.post<NearByLinesResponse>(url, body)
  }


  getIpLocation(ip?: string) {
    const url = this.gateway + '/amap/ip_to_location';
    return this.http.get(url);
  }

  getAddressLocation(address: string) {
    const url = this.gateway + '/amap/address_to_location?address=' + address;
    return this.http.get(url);
  }

  getPOILite(words: string, category: string = "999901%7C999907%7C999916%7C150700"): Observable<PoiSearchResponse> {
    const url = this.gateway + '/amap/poi_tips_lite?' + `words=${words}&adcode=true&category=${category}`;
    return this.http.get<PoiSearchResponse>(url)
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
