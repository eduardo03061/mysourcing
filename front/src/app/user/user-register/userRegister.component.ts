import { Component } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { DataService } from '../../core/services/data.service';
import { AsyncPipe } from '@angular/common';
import { EMPTY, catchError } from 'rxjs';
import { RouterLink, RouterModule } from '@angular/router';

@Component({
    selector: 'app-user',
    standalone: true,
    imports: [FormsModule,
        ReactiveFormsModule, AsyncPipe, RouterLink, RouterModule],
    templateUrl: './userRegister.component.html',
    styleUrl: './userRegister.component.css'
})


export class UserRegisterComponent {
    constructor(private dataService: DataService) { }
    public errorMessage!: string;
    names = '';
    firstSurname = '';
    secondSurname = '';
    email = '';

    phone = '';

    zipCode = '';
    state = '';


    setZipCode() {
        this.dataService.sendZipCode(this.zipCode).subscribe(resp => {
            resp = JSON.parse(resp);
            this.state = resp;
        });
    }


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

        this.dataService.sendDataUser(data).subscribe(
            { 
                error: err =>{
                    alert('Error al crear usuario, revisa que los campos esten correctos.') 
                },
                complete: () => {
                    alert('Creado con exito!');


                    this.names = '';
                    this.firstSurname = '';
                    this.secondSurname = '';
                    this.email = '';
                    this.phone = '';
                    this.zipCode = '';
                    this.state = '';
                }
              });
    };
}
