import { Component } from '@angular/core';
import { DataService } from '../../core/services/data.service';
import { EMPTY, Observable, catchError } from 'rxjs';
import { AsyncPipe } from '@angular/common';

@Component({
  selector: 'app-user',
  standalone: true,
  imports: [AsyncPipe],
  templateUrl: './userList.component.html',
  styleUrl: './userList.component.css'
})
export class UserListComponent {

  public usersResults$!: Observable<any>;
  public errorMessage!: string;
  constructor(private dataService: DataService) { }



  ngOnInit() {
    this.dataService.getUsers(1).subscribe(resp => {
       
      
      resp = JSON.parse(resp);
      console.log('resp',resp);
      this.usersResults$ = resp;
  });
    console.log('results',this.usersResults$)
  }



}