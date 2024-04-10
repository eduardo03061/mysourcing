import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class DataService {
  private apiUrl = 'http://localhost:8000/api/users'; // Reemplaza con la URL de tu API

  constructor(private http: HttpClient) { }

  sendDataUser(data: any): Observable<any> {
    return this.http.post(this.apiUrl, data);
  }
}