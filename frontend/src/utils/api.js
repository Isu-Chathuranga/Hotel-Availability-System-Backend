import axios from 'axios';

const API_BASE = process.env.REACT_APP_API_URL || 'http://localhost:8000';

const api = axios.create({
  baseURL: API_BASE,
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
  },
});

export const authAPI = {
  register: (data) => api.post('/api/auth/register', data),
  registerOwner: (data) => api.post('/api/auth/register-owner', data),
  login: (data) => api.post('/api/auth/login', data),
  logout: () => api.post('/api/auth/logout'),
  checkSession: () => api.get('/api/auth/check_session'),
};

export const hotelsAPI = {
  list: (search) => api.get('/api/hotels/list', { params: { search } }),
  get: (id) => api.get('/api/hotels/get', { params: { id } }),
  create: (data) => api.post('/api/hotels/create', data),
  update: (data) => api.put('/api/hotels/update', data),
  delete: (id) => api.delete('/api/hotels/delete', { params: { id } }),
  search: (params) => api.get('/api/hotels/search', { params }),
  locationCounts: () => api.get('/api/hotels/location-counts'),
};

export const roomsAPI = {
  list: (hotelId) => api.get('/api/rooms/list', { params: { hotel_id: hotelId } }),
  create: (data) => api.post('/api/rooms/create', data),
  update: (data) => api.put('/api/rooms/update', data),
  delete: (id) => api.delete('/api/rooms/delete', { params: { id } }),
};

export const bookingsAPI = {
  create: (data) => api.post('/api/bookings/create', data),
  listUser: () => api.get('/api/bookings/list_user'),
  listOwner: () => api.get('/api/bookings/list_owner'),
  confirm: (id) => api.put('/api/bookings/confirm', { id }),
  cancel: (id) => api.put('/api/bookings/cancel', { id }),
};

export const eventsAPI = {
  list: (hotelId) => api.get('/api/events/list', { params: { hotel_id: hotelId } }),
  create: (data) => api.post('/api/events/create', data),
  update: (data) => api.put('/api/events/update', data),
  delete: (id) => api.delete('/api/events/delete', { params: { id } }),
};

export const offersAPI = {
  list: (hotelId) => api.get('/api/offers/list', { params: { hotel_id: hotelId } }),
  create: (data) => api.post('/api/offers/create', data),
  update: (data) => api.put('/api/offers/update', data),
  delete: (id) => api.delete('/api/offers/delete', { params: { id } }),
};

export const placesAPI = {
  list: (hotelId) => api.get('/api/places/list', { params: { hotel_id: hotelId } }),
  create: (data) => api.post('/api/places/create', data),
  update: (data) => api.put('/api/places/update', data),
  delete: (id) => api.delete('/api/places/delete', { params: { id } }),
};

export const adminAPI = {
  hotels: () => api.get('/api/admin/hotels'),
  deleteHotel: (id) => api.delete('/api/admin/delete_hotel', { params: { id } }),
};

export default api;
