import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse, HttpHeaders } from '@angular/common/http';
import { Observable, catchError, throwError } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class DataService {
  private apiUrlUsers = 'http://localhost:8000/api/users'; // Reemplaza con la URL de tu API
  private apiUrlZipCode = 'http://localhost:8000/api/zipcode'; // Reemplaza con la URL de tu API

  constructor(private http: HttpClient) { }



  getUsers(page: any, query:string): Observable<any> {

    return this.http.get(`${this.apiUrlUsers}?page=${page}&${query}`) 

  }

  sendZipCode(zipCode: any): Observable<any> {

    return this.http.get(`${this.apiUrlZipCode}/${zipCode}`, {
      headers: new HttpHeaders().set('Content-Type', 'application/json'),
      responseType: 'text'
    }).pipe(catchError((error: HttpErrorResponse) => {
      let errorMessage = "";
      if (error.error instanceof ErrorEvent) {
        errorMessage = `Error: ${error.error.message}`;
      } else {
        errorMessage = `Error code: ${error.status}, message: ${error.message}`
      }


      return throwError(() => errorMessage)
    }));

  }

  sendDataUser(data: any): Observable<any> {
    return this.http.post(this.apiUrlUsers, data, {
      headers: new HttpHeaders().set('Content-Type', 'application/json'),
      responseType: 'text'
    }).pipe(catchError((error: HttpErrorResponse) => {
      let errorMessage = "";
      if (error.error instanceof ErrorEvent) {
        errorMessage = `Error: ${error.error.message}`;
      } else {
        errorMessage = `Error code: ${error.status}, message: ${error.message}`
      }


      return throwError(() => errorMessage)
    }));




  }
}