import React, { useContext, useEffect, useState } from "react";
import useAsyncEffect from "use-async-effect";
import { Machine } from "../models/Machine";
import { Token } from "../models/Token";
import { decode_jwt } from "../utils/decode_jwt";
import { useAuth } from "./AuthProvider";

export type Sshelter = {
    loading: boolean;
    machines: Machine[];

    isEditPaneRequesting: boolean;
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
    const [state, setState] = useState<Sshelter>(initialState);

    const refresh = async () => {
        setState({...state, loading: true})
        const request = await fetch('/api/machines', {
            headers: {
                'Authorization': 'Bearer ' + auth.token,
            },
        });

        const resp = await request.json();
        setState({...state, loading: false, machines: resp['hydra:member']});
    };

    const createMachine = async (machine: Machine) => {
        setState({...state, isEditPaneRequesting: true})
        await fetch('/api/machines', {
            'method': 'POST',
            'headers': {
                'Authorization': 'Bearer ' + auth.token,
            },
            'body': JSON.stringify(machine),
        });
        // @TODO => display a snackbar to say if it worked or not
        // Handle validation 
        setTimeout(() => setState({...state, isEditPaneRequesting: true}), 2000)

        refresh();
    }

    const updateMachine = async (machine: Machine) => {
        setState({...state, isEditPaneRequesting: true})
        console.log('Update machine');
        // @TODO => display a snackbar to say if it worked or not
        // Handle validation 
        setTimeout(() => setState({...state, isEditPaneRequesting: true}), 2000)
        refresh();
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