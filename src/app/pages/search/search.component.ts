import { Component, OnInit } from '@angular/core';
import {debounceTime, distinctUntilChanged, map, switchMap} from "rxjs/operators";
import {PoiSearchResponse, TipList} from "../../shared/data/amap";
import {Observable, Subject} from "rxjs";
import {AmapService} from "../../shared/services/amap.service";
import {ActivatedRoute} from "@angular/router";

@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.sass']
})
export class SearchComponent implements OnInit {

  private term$ = new Subject<string>();
  complete$: Observable<TipList[]>;
  keywords: string;

  constructor(private amapService: AmapService, private route: ActivatedRoute) {
  }

  ngOnInit(): void {
    this.complete$ = this.term$.pipe(
      debounceTime(400),
      distinctUntilChanged(),
      switchMap(term => this.amapService.getPoiSearch(term)),
      map<PoiSearchResponse, TipList[]>(res => {
        if (res.hasOwnProperty("tip_list")) {
          if (res.tip_list.length > 0) {
            return res.tip_list;
          }
        }
        return [];
      })
    );
    this.route.queryParams.subscribe((params) => {
      if (params.hasOwnProperty("keywords") && params["keywords"].length > 0 ) {
        this.keywords = params["keywords"];
        this.term$.next(params["keywords"])
      }
    });
  }

}
