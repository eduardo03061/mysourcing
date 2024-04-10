import { Routes } from "@angular/router";
import { UserListComponent } from "./user-list/userList.component";
import { UserRegisterComponent } from "./user-register/userRegister.component";

export const USER_ROUTES: Routes = [
    { path: '', component: UserListComponent },
    { path: 'register', component: UserRegisterComponent }
]