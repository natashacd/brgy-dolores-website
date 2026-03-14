const dataStore = {
  users: null,
  roles: null,
  residents: null,
  timestamp: null,
};

export const setUsers = (users) => {
  dataStore.users = users;
  dataStore.timestamp = Date.now();
};

export const setRoles = (roles) => {
  dataStore.roles = roles;
  dataStore.timestamp = Date.now();
};

export const setResidents = (residents) => {
  dataStore.residents = residents;
  dataStore.timestamp = Date.now();
};

export const getUsers = () => dataStore.users;

export const getRoles = () => dataStore.roles;

export const getResidents = () => dataStore.residents;

export const hasData = () => {
  return dataStore.users !== null && dataStore.roles !== null && dataStore.residents !== null;
};

export const hasResidentsData = () => {
  return dataStore.residents !== null;
};

export const clearData = () => {
  dataStore.users = null;
  dataStore.roles = null;
  dataStore.residents = null;
  dataStore.timestamp = null;
};

export default {
  setUsers,
  setRoles,
  setResidents,
  getUsers,
  getRoles,
  getResidents,
  hasData,
  hasResidentsData,
  clearData,
};