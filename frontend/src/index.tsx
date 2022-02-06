import { ThemeProvider } from '@emotion/react';
import { createTheme } from '@mui/material';
import React from 'react';
import ReactDOM from 'react-dom';
import './assets/css/main.scss';
import App from './pages/App';
import Login from './pages/Login';
import { AuthProvider, useAuth } from './hooks/AuthProvider';

const theme = createTheme({
  palette: {
    mode: 'dark',
  },
})

const AuthWrapper = () => {
  const auth = useAuth();

  if (!auth.isAuthenticated()) {
    return <Login />;
  }

  return <App />
};

ReactDOM.render(
  <React.StrictMode>
    <ThemeProvider theme={theme}>
      <AuthProvider>
        <AuthWrapper />
      </AuthProvider>
    </ThemeProvider>
  </React.StrictMode>,
  document.getElementById('root')
);