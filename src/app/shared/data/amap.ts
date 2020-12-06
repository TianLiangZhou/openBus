export interface Trip {
  speed_avg: string;
  arrival: string;
  station_left: string;
  uuid: string;
  speed: string;
  dis: string;
  gps_id: string;
  x: string;
  y: string;
  delay_time: string;
}

export interface Bus {
  sub_text: string;
  schedule: string;
  start_time: string;
  trip: Trip[];
  sub_status: string;
  line: string;
  station: string;
  end_time: string;
  miss_time: string;
  status: string;
}

export interface LineStationResponse {
  buses: Bus[];
  code: string;
  message: string;
  result: string;
  timestamp: string;
  version: string;
}


export interface Suggestion {
}

export interface Lqii {
  is_view_city: string;
  self_navigation: string;
  querytype: string;
  is_current_city: string;
}

export interface MagicboxData {
}

export interface Stations {
  businfo_lineids: string;
  display_x: string;
  display_y: string;
  businfo_ids: string;
  businfo_line_alias: string;
  businfo_ui_colors: string;
  businfo_alias: string;
  businfo_angles: string;
  businfo_line_names: string;
  businfo_stationids: string;
  businfo_line_types: string;
  businfo_road_ids: string;
  businfo_station_status: string;
  businfo_meshs: string;
  xs: string;
  ys: string;
  businfo_line_keys: string;
  businfo_expect_dates: string;
  businfo_realbus: string;
}

export interface PoiList {
  distance: string;
  businfo_station_status: string;
  name: string;
  typecode: string;
  areacode: string;
  adcode: string;
  update_flag: string;
  cityname: string;
  longitude: string;
  id: string;
  end_poi_extension: string;
  address: string;
  latitude: string;
  newtype: string;
  stations: Stations;
}

export interface InfoliteResponse {
  busline_list: any[];
  bus_list: any[];
  codepoint: number;
  code: string;
  suggestion: Suggestion;
  general_flag: string;
  busline_count: string;
  timestamp: string;
  lqii: Lqii;
  is_general_search: string;
  version: string;
  result: string;
  magicbox_data: MagicboxData;
  keywords: string;
  message: string;
  total: string;
  interior_count: string;
  poi_list: PoiList[];
}



export interface BusLite {
  arrival: string;
  speed_avg: string;
  station_left: string;
  y: string;
  x: string;
  speed: string;
  dis: string;
}

export interface Line {
  status: string;
  index: number;
  name: string;
  buses: BusLite[];
  key_name: string;
  start_time: string;
  stationid: string;
  end_time: string;
  lineid: string;
  type: string;
  is_realtime: string;
}

export interface Data {
  lines: Line[];
  station_ids: string;
  line_ids: string;
}

export interface LineResponse {
  code: string;
  timestamp: string;
  version: string;
  result: string;
  message: string;
  data: Data;
}



export interface TimeDescJson {
}

export interface IrregularTime {
}

export interface Emergency {
}

export interface Subway {
  color: string;
  status: string;
  line_key: string;
}

export interface Station {
  status: string;
  station_num: string;
  code: string;
  poiid2: string;
  poiid1: string;
  start_time: string;
  spell: string;
  station_id: string;
  alias: string;
  tips_type: string;
  end_time: string;
  terminal_status: any[];
  xy_coords: string;
  name: string;
  subways: Subway[];
  selected?: boolean;
}

export interface BuslineList {
  front_name?: string;
  areacode?: string;
  expect_open_date?: string;
  total_price_air?: string;
  bounds?: string;
  direc?: string;
  id?: string;
  xs?: string;
  is_emergency?: number;
  terminal_spell?: string;
  time_desc_json?: TimeDescJson;
  terminal_name?: string;
  color?: string;
  tips_type?: string;
  basic_price?: string;
  timetable?: string;
  type?: string;
  start_time?: string;
  key_name?: string;
  status?: string;
  ic_card?: string;
  irregular_time?: IrregularTime;
  description?: string;
  emergency?: Emergency;
  auto?: string;
  company?: string;
  front_spell?: string;
  description1?: string;
  ys?: string;
  total_price?: string;
  name?: string;
  basic_price_air?: string;
  stations?: Station[];
  interval?: string;
  air?: string;
  alias?: string;
  length?: string;
  end_time?: string;
  line_feature?: string;
  loop?: string;
  is_realtime?: string;
}

export interface LineDetailResponse {
  code: number;
  busline_count: number;
  timestamp: string;
  version: string;
  result: boolean;
  message: string;
  total: number;
  busline_list: BuslineList[];
}




export interface Trip {
  d: string;
  i: string;
  stid: string;
  s: string;
  t: string;
  y: string;
  x: string;
}

export interface Td {
  d: string;
  t: string;
  ti: string;
  ls: string;
}

export interface St {
  tw: string;
  td: Td[];
  id: string;
  ta: string;
}

export interface BusEx {
  line: string;
  trip: Trip[];
  st: St[];
}

export interface LineDetailExResponse {
  code: string;
  buses: BusEx[];
  timestamp: string;
  flag: string;
  version: string;
  result: string;
  message: string;
}



export interface Position {
  className: string;
  kT: number;
  KL: number;
  lng: number;
  lat: number;
  pos: number[];
}

export interface GeolocationPosition {
  status: number;
  code: number;
  info: string;
  position: Position;
  location_type: string;
  message: string;
  accuracy: number;
  isConverted: boolean;
  altitude: number;
  altitudeAccuracy: number;
  heading?: any;
  speed?: any;
}




export interface NearBus {
  arrival: string;
  station_left: string;
  y: string;
  x: string;
}

export interface NearLine {
  status: string;
  index: number;
  front_name: string;
  name: string;
  buses: NearBus[];
  key_name: string;
  start_time: string;
  interval: string;
  terminal_name: string;
  stationid: string;
  end_time: string;
  lineid: string;
  is_realtime: string;
}

export interface NearByLinesData {
  lines: NearLine[];
  station_ids: string;
  x: string;
  y: string;
  line_ids: string;
  station_name: string;
}

export interface NearByLinesResponse {
  code: string;
  timestamp: string;
  version: string;
  result: string;
  message: string;
  data: NearByLinesData;
}


export interface Tip {
  category: string;
  name: string;
  district: string;
  ignore_district: string;
  adcode: string;
  rank: string;
  datatype_spec: string;
  datatype: string;
  terminals: string;
  line_distance: string;
  city_name: string;
  poiid: string;
  province_name: string;
}

export interface TipList {
  tip: Tip;
}

export interface PoiSearchResponse {
  code?: string;
  timestamp?: string;
  tip_list: TipList[];
  is_general_search?: string;
  version?: string;
  result?: string;
  message?: string;
  total?: string;
}
