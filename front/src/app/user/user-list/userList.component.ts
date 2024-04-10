import { Component, ElementRef, ViewChild } from '@angular/core';
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


  search = '';



  ngOnInit() { 
    this.usersResults$ = this.dataService.getUsers(1,'').pipe(catchError((error: string) => {
      this.errorMessage = error;
      return EMPTY;
    }));


    
    console.log('results', this.usersResults$)
  }


  searchData(){
    console.log('eee',this.search)
    this.usersResults$ = this.dataService.getUsers(1,`query=${this.search}`).pipe(catchError((error: string) => {
      this.errorMessage = error;
      return EMPTY;
    }));
  }



}