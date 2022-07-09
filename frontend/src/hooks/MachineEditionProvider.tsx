import React, { useContext, useState } from "react";
import { Machine } from "../models/Machine";
import { useSshelter } from "./SshelterProvider";
import { Box, Button, Modal, Paper, Typography } from "@mui/material";

const modalStyle = {
    position: 'absolute' as 'absolute',
    top: '50%',
    left: '50%',
    transform: 'translate(-50%, -50%)',
    width: 400,
    border: '2px solid #000',
    boxShadow: 24,
    p: 4,
};

export type MachineEdition = {
    machine: Machine | null;
};

export type MachineEditionCtx = MachineEdition & {
    setMachine: (machine: Machine | null) => void;
    save: (m: Machine) => void;
    remove: () => void;
};

const initialState: MachineEdition = {
    machine: null,
};

const MachineEditionContext = React.createContext<MachineEditionCtx>({
    ...initialState,
    setMachine: (machine: Machine | null) => { },
    save: () => { },
    remove: () => { },
});

export function MachineEditionProvider({ children }: { children: React.ReactNode }) {
    const sshelter = useSshelter();
    const [modalOpened, setModalOpened] = useState<boolean>(false);
    const [state, setState] = useState<MachineEdition>(initialState);

    const setMachine = (machine: Machine | null) => { setState({ ...state, machine }) };

    const remove = async () => {
        if (!state.machine) {
            return;
        }

        // @TODO: deleteMachine returns errors and snackbar if there is one
        await sshelter.deleteMachine(state.machine);
        setState({ ...state, machine: null });
        setModalOpened(false);
    };

    const save = async (m: Machine) => {
        if (!m) {
            return;
        }

        let response = null;
        if (!!m['@id'] && m['@id'].length > 0) {
            response = sshelter.updateMachine(m);
        } else {
            response = sshelter.createMachine(m);
        }

        return response;
    };

    return <MachineEditionContext.Provider value={{
        ...state,
        setMachine,
        save,
        remove: () => setModalOpened(true),
    }}>
        {children}

        <Modal open={modalOpened} onClose={() => setModalOpened(false)}>
            <Paper sx={modalStyle}>
                {
                    !state.machine &&
                    <>
                        <Typography id="modal-modal-title" variant="h6" component="h2">
                            Something went wrong
                        </Typography>
                        <Typography id="modal-modal-description" variant="h6" component="h2">
                            You should no be there. No machine selected but still trying to delete it...
                        </Typography>
                    </>

                }
                {
                    state.machine && <>
                        <Typography id="modal-modal-title" variant="h6" component="h2">
                            Deleting {state.machine?.name}
                        </Typography>
                        <Typography id="modal-modal-description" sx={{ mt: 2 }}>
                            Are you sure you want to remove this machine ?
                        </Typography>

                        <Box style={{ display: 'flex', justifyContent: 'end', marginTop: '1em' }}>
                            <Button onClick={() => setModalOpened(false)}>Cancel</Button>
                            <Button onClick={() => remove()} color="error">Delete</Button>
                        </Box>

                    </>
                }
            </Paper>
        </Modal >
    </MachineEditionContext.Provider >
}

export function useMachineEditor() {
    return useContext<MachineEditionCtx>(MachineEditionContext);
}