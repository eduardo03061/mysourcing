import { Routes } from '@angular/router';
import { MenuComponent } from './menu/menu.component';

export const routes: Routes = [
    {
        path:'', component: MenuComponent
    },
    {
        path:'users', loadChildren: ()=> import('./user/user.routes').then(m=>m.USER_ROUTES)
    },
];
