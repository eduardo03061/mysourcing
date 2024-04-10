import { Component } from '@angular/core';
import { DataService } from '../../core/services/data.service';
import { EMPTY, Observable, catchError } from 'rxjs';
import { AsyncPipe } from '@angular/common';
import { UserItemComponent } from '../../components/user-item/user-item.component';

@Component({
  selector: 'app-user',
  standalone: true,
  imports: [AsyncPipe, UserItemComponent],
  templateUrl: './userList.component.html',
  styleUrl: './userList.component.css'
})
export class UserListComponent {

  public usersResults$!: Observable<any>;
  public errorMessage!: string;
  constructor(private dataService: DataService) { }



  ngOnInit() { 
    this.usersResults$ = this.dataService.getUsers(1).pipe(catchError((error: string) => {
      this.errorMessage = error;
      return EMPTY;
    }));


    
    console.log('results', this.usersResults$)
  }



}