import { Component } from '@angular/core';

@Component({
  selector: 'app-user',
  standalone: true,
  imports: [],
  templateUrl: './userList.component.html',
  styleUrl: './userList.component.css'
})
export class UserListComponent {
  username ="eduardo";
  isLoggin = true;
}