import { Box, Button, Divider, Drawer, List, ListItem, ListItemIcon, ListItemText, Toolbar, Typography } from "@mui/material";
import StorageIcon from '@mui/icons-material/Storage';
import AddIcon from '@mui/icons-material/Add';
import { useSshelter } from "../hooks/SshelterProvider";
import { Machine } from "../models/Machine";

type MenuProps = {
    opened: boolean;
    setMenuOpened: (opened: boolean) => void;
    selectMachine: (m: Machine) => void;
    addMachine: () => void;
}

export const drawerWidth = 240;

export default function SideMenu({ opened, setMenuOpened, selectMachine, addMachine }: MenuProps) {
    const sshelter = useSshelter();

    const drawer = <div style={{display: 'flex', flexDirection: 'column', height: '100%', maxHeight: '100%'}}>
        <Typography variant="h5" m={2}>Machines</Typography>
        <List>
            {
                sshelter.machines.map((m: Machine) => (
                    <ListItem button key={m['@id']} onClick={() => { selectMachine(m); setMenuOpened(false); }}>
                        <ListItemIcon><StorageIcon /></ListItemIcon>
                        <ListItemText primary={
                            m.name + (m.shortName ? " (" + m.shortName + ")" : "")
                        } />
                    </ListItem>
                ))
            }
        </List>
        <Divider style={{flex: 1}}/>
        <Toolbar style={{display: 'flex', alignItems: 'center', justifyContent: 'center'}}>
            <Button variant='outlined' endIcon={<AddIcon/>} onClick={addMachine}>Add machine</Button>
        </Toolbar>
    </div>

    return <Box sx={{ display: 'flex' }}>
        <Box component="nav" sx={{ width: { sm: drawerWidth }, flexShrink: { sm: 0 } }}>
            <Drawer variant="temporary" open={opened} onClose={() => setMenuOpened(false)} ModalProps={{ keepMounted: true }} sx={{ display: { xs: 'block', sm: 'none' }, '& .MuiDrawer-paper': { boxSizing: 'border-box', width: drawerWidth } }}>
                {drawer}
            </Drawer>
            <Drawer variant="permanent" sx={{ display: { xs: 'none', sm: 'block' }, '& .MuiDrawer-paper': { boxSizing: 'border-box', width: drawerWidth } }} open>
                {drawer}
            </Drawer> 
        </Box>
    </Box>
}