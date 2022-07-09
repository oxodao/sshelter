import React, { useContext, useState } from "react";
import useAsyncEffect from "use-async-effect";
import { Machine } from "../models/Machine";
import { useApi } from "./ApiRequest";
import { useAuth } from "./AuthProvider";

export type Sshelter = {
    loading: boolean;
    machines: Machine[];

    isEditPaneRequesting: boolean;
    currentlyEditedMachine: Machine|null;
};

export type SshelterCtx = Sshelter & {
    refresh: () => void;
    createMachine: (m: Machine) => void;
    updateMachine: (m: Machine) => void;
    deleteMachine: (m: Machine) => void;
};

const initialState: Sshelter = {
    loading: true,
    machines: [],
    isEditPaneRequesting: false,
    currentlyEditedMachine: null,
};

const SshelterContext = React.createContext<SshelterCtx>({
    ...initialState,
    refresh: () => {},
    createMachine: (m: Machine) => {},
    updateMachine: (m: Machine) => {},
    deleteMachine: (m: Machine) => {},
});

export function SshelterProvider({children}: {children: React.ReactNode}) {
    const auth = useAuth();
    const api = useApi();
    const [state, setState] = useState<Sshelter>(initialState);

    const refresh = async () => {
        setState({...state, loading: true})
        setState({...state, loading: false, machines: (await api("/api/machines"))['hydra:member']});
    };

    const createMachine = async (machine: Machine) => {
        machine.port = typeof machine.port === "string" ? parseInt(machine.port) : machine.port;
        setState({...state, isEditPaneRequesting: true})
        await fetch('/api/machines', {
            'method': 'POST',
            'headers': {
                'Authorization': 'Bearer ' + auth.token,
                'Content-Type': 'application/ld+json',
                'Accept': 'application/ld+json',
            },
            'body': JSON.stringify(machine),
        });
        // @TODO => display a snackbar to say if it worked or not
        // Handle validation 
        setTimeout(() => setState({...state, isEditPaneRequesting: true}), 2000)

        await refresh();
    }

    const updateMachine = async (machine: Machine) => {
        machine.port = typeof machine.port === "string" ? parseInt(machine.port) : machine.port;
        setState({...state, isEditPaneRequesting: true})
        await fetch(machine['@id'], {
            'method': 'PUT',
            'headers': {
                'Authorization': 'Bearer ' + auth.token,
                'Content-Type': 'application/ld+json',
                'Accept': 'application/ld+json',
            },
            'body': JSON.stringify(machine),
        });
        // @TODO => display a snackbar to say if it worked or not
        // Handle validation 
        setTimeout(() => setState({...state, isEditPaneRequesting: true}), 2000)
        await refresh();
    }

    const deleteMachine = async (machine: Machine) => {
        const resp = await fetch(machine['@id'], {
            'method': 'DELETE',
            'headers': {
                'Authorization': 'Bearer ' + auth.token,
            }
        });

        // @TODO => Display a snackbar on error

        await refresh();
    }

    useAsyncEffect(async () => {
        await refresh();
    }, []);

    return <SshelterContext.Provider value={{
        ...state,
        refresh,
        createMachine,
        updateMachine,
        deleteMachine,
    }}>
        {children}
    </SshelterContext.Provider>
}

export function useSshelter() {
    return useContext<SshelterCtx>(SshelterContext);
}