import { Component } from '@angular/core';
import { DataService } from '../../core/services/data.service';
import { EMPTY, Observable, catchError } from 'rxjs';
import { AsyncPipe } from '@angular/common';
import { RouterLink, RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

@Component({
  selector: 'app-user',
  standalone: true,
  imports: [AsyncPipe, RouterLink, RouterModule, FormsModule,
    ReactiveFormsModule],
  templateUrl: './userList.component.html',
  styleUrl: './userList.component.css'
})
export class UserListComponent {
  public usersResults$!: Observable<any>;
  public errorMessage!: string;
  constructor(private dataService: DataService) { }

  pages = 1;

  search = '';
  ngOnInit() {
    this.usersResults$ = this.dataService.getUsers(this.pages, '').pipe(catchError((error: string) => {
      this.errorMessage = error;
      return EMPTY;
    }));
  }

  searchData() {
    this.usersResults$ = this.dataService.getUsers(this.pages, `query=${this.search}`).pipe(catchError((error: string) => {
      this.errorMessage = error;
      return EMPTY;
    }));
  }


  nextPage() {
    this.pages = this.pages + 1;
    this.searchData()
  }

  prevPage() {
    this.pages = this.pages - 1;
    this.searchData()
  }



}