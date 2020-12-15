import {AfterViewInit, Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {debounceTime, distinctUntilChanged, map, switchMap} from "rxjs/operators";
import {PoiSearchResponse, TipList} from "../../shared/data/amap";
import {Observable, of, Subject} from "rxjs";
import {AmapService} from "../../shared/services/amap.service";
import {ActivatedRoute, Router} from "@angular/router";
import {ProxyService} from "../../shared/services/proxy.service";

@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.sass']
})
export class SearchComponent implements OnInit, AfterViewInit {

  private term$ = new Subject<string>();
  complete$: Observable<TipList[]>;
  keywords: string;

  @ViewChild('searchContent', {static: true}) searchContent: ElementRef;
  loading = true;

  constructor(private amapService: ProxyService,
              private router: Router,
              private route: ActivatedRoute) {
  }

  ngOnInit(): void {
    const content = (this.searchContent.nativeElement as HTMLDivElement);
    content.style.height = (window.innerHeight - 68) + "px";
    this.complete$ = this.term$.pipe(
      debounceTime(400),
      distinctUntilChanged(),
      switchMap(term => {
        if (term.length < 1) {
          return of<PoiSearchResponse>({tip_list: []});
        }
        this.loading = true;
        return this.amapService.getPOILite(term)
      }),
      map<PoiSearchResponse, TipList[]>(res => {
        console.log(res)
        this.loading = false;
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
      }
    });
  }



  onSearch($event: KeyboardEvent) {
    this.keywords = ($event.target as HTMLInputElement).value;
    this.term$.next(this.keywords);
  }

  ngAfterViewInit(): void {
    this.term$.next(this.keywords);
  }

  onClean() {
    this.keywords = "";
    this.term$.next("");
    (document.getElementById('searchInput') as HTMLInputElement).focus();
  }

  itemClick(item: TipList) {
    switch (item.tip.category) {
      case "999901": // 线路类型
        this.router.navigateByUrl("/line/" + item.tip.poiid).then(() => {

        });
        break;
      case "150700": // 站点名类型
        break;
    }
  }
}
