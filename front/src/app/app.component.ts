import { Component } from '@angular/core';
import { RouterLink, RouterModule, RouterOutlet } from '@angular/router';
 
import { MenuComponent } from './menu/menu.component';
import { UserRegisterComponent } from './user/user-register/userRegister.component';
 
@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, MenuComponent, UserRegisterComponent, RouterLink, RouterModule],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  title = 'front';
}
