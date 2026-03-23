const dataStore = {
  users: null,
  roles: null,
  residents: null,
  serviceRequests: null,
  luponCases: null,
  announcements: null,
  complaints: null,
  auditLogs: null,
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

export const setServiceRequests = (requests) => {
  dataStore.serviceRequests = requests;
  dataStore.timestamp = Date.now();
};

export const setLuponCases = (cases) => {
  dataStore.luponCases = cases;
  dataStore.timestamp = Date.now();
};

export const setComplaints = (complaints) => {
  dataStore.complaints = complaints;
  dataStore.timestamp = Date.now();
};

export const setAuditLogs = (logs) => {
  dataStore.auditLogs = logs;
  dataStore.timestamp = Date.now();
};

export const setAnnouncements = (announcements) => {
  dataStore.announcements = announcements;
  dataStore.timestamp = Date.now();
};

export const getUsers = () => dataStore.users;

export const getRoles = () => dataStore.roles;

export const getResidents = () => dataStore.residents;

export const getServiceRequests = () => dataStore.serviceRequests;

export const getLuponCases = () => dataStore.luponCases;

export const getComplaints = () => dataStore.complaints;

export const getAnnouncements = () => dataStore.announcements;

export const getAuditLogs = () => dataStore.auditLogs;


export const hasData = () => {
  return (
    dataStore.users !== null &&
    dataStore.roles !== null &&
    dataStore.residents !== null
  );
};

export const hasResidentsData = () => {
  return dataStore.residents !== null;
};

export const hasServiceRequestsData = () => dataStore.serviceRequests !== null;

export const hasLuponCasesData = () => dataStore.luponCases !== null;

export const hasAnnouncementsData = () => dataStore.announcements !== null;

export const hasComplaintsData = () => dataStore.complaints !== null;

export const hasAuditLogsData = () => dataStore.auditLogs !== null;


export const clearAnnouncementsData = () => {
  dataStore.announcements = null;
};

export const clearData = () => {
  dataStore.users = null;
  dataStore.roles = null;
  dataStore.residents = null;
  dataStore.serviceRequests = null;
  dataStore.luponCases = null;
  dataStore.complaints = null;
  dataStore.announcements = null;
  dataStore.auditLogs = null;
  dataStore.timestamp = null;
};


export default {
  setUsers,
  setRoles,
  setResidents,
  setServiceRequests,
  setLuponCases,
  setComplaints,
  setAnnouncements,
  setAuditLogs,
  getUsers,
  getRoles,
  getResidents,
  getServiceRequests,
  getLuponCases,
  getComplaints,
  getAnnouncements,
  getAuditLogs,
  hasData,
  hasResidentsData,
  hasAnnouncementsData,
  hasComplaintsData,
  hasAuditLogsData,
  clearData,
  clearAnnouncementsData,
};
