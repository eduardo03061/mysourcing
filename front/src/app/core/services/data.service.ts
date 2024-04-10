import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, catchError, throwError } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class DataService {
  private apiUrl = 'http://localhost:8000/api/users'; // Reemplaza con la URL de tu API

  constructor(private http: HttpClient) { }

  sendDataUser(data: any): Observable<any> {
    console.log('data',data);
    
    return this.http.post(this.apiUrl, data).pipe(catchError((error:HttpErrorResponse)=>{
        let errorMessage = "";
        if(error.error instanceof ErrorEvent){
            errorMessage = `Error: ${error.error.message}`;
        }else{
            errorMessage = `Error code: ${error.status}, message: ${error.message}`
        }


        return throwError(()=>errorMessage)
    }));



    
  }
}