import { AppBar, IconButton, Toolbar, Tooltip, Typography } from '@mui/material';
import React, { useState } from 'react';
import { SshelterProvider } from '../hooks/SshelterProvider';
import EditPane from '../components/EditPane';
import SideMenu, { drawerWidth } from '../components/SideMenu';
import MenuIcon from '@mui/icons-material/Menu';
import LogoutIcon from '@mui/icons-material/Logout';
import { useAuth } from '../hooks/AuthProvider';
import { MachineEditionProvider } from '../hooks/MachineEditionProvider';


function App() {
  const auth = useAuth();
  const [menuOpened, setMenuOpened] = useState<boolean>(false);

  return <SshelterProvider>
    <MachineEditionProvider>
      <AppBar position="fixed" sx={{ width: { sm: `calc(100% - ${drawerWidth}px)` }, ml: { sm: `${drawerWidth}px` } }}>
        <Toolbar>
          <IconButton color="inherit" aria-label="open drawer" edge="start" onClick={() => setMenuOpened(true)} sx={{ mr: 2, display: { sm: 'none' } }}>
            <MenuIcon />
          </IconButton>
          <Typography variant="h6" noWrap component="div" style={{ flex: '1' }}>SSHelter</Typography>
          <Tooltip title="Logout">
            <IconButton color="primary" onClick={() => auth.logout()}>
              <LogoutIcon />
            </IconButton>
          </Tooltip>
        </Toolbar>
      </AppBar>

      <SideMenu opened={menuOpened} setMenuOpened={setMenuOpened} />
      <EditPane />
    </MachineEditionProvider>
  </SshelterProvider>
}

export default App;
