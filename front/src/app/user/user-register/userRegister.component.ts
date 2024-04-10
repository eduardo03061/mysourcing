import { Component } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
 

@Component({
    selector: 'app-user',
    standalone: true,
    imports: [FormsModule,
        ReactiveFormsModule],
    templateUrl: './userRegister.component.html',
    styleUrl: './userRegister.component.css'
})

  
export class UserRegisterComponent {
  
    names='';
    firstSurname='';
    secondSurname='';
    email='';

    phone='';

    zipCode = 28984;
    state = 'Colima';

    sendForm() {
        const data = {
            names: this.names,
            firstSurname: this.firstSurname,
            secondSurname: this.secondSurname,
            email: this.email,
            phone: this.phone,
            zipCode: this.zipCode,
            state: this.state

        }
        console.log({data})

       
    };
}
