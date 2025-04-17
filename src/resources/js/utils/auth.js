// src/utils/auth.js
export function getUser() {
    const u = localStorage.getItem('user')
    return u ? JSON.parse(u) : null
  }
  
  export function getToken() {
    return localStorage.getItem('token') || ''
  }
  
  export function isAuthenticated() {
    return !!localStorage.getItem('token')
  }
  
  export function logoutUser() {
    localStorage.removeItem('user')
    localStorage.removeItem('token')
  }
  