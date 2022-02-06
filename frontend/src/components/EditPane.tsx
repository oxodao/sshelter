import { Box, Button, Modal, Paper, TextareaAutosize, TextField, Typography } from "@mui/material"
import { useState } from "react";
import { Controller, useForm } from "react-hook-form";
import { useSshelter } from "../hooks/SshelterProvider";
import { Machine } from "../models/Machine"

type EditProps = {
    machine: Machine | null;
}

const styles = {
    marginTop: '.5em',
    marginBottom: '.25em',
};

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

export default function EditPane({ machine }: EditProps) {
    const sshelter = useSshelter();
    const [modalOpened, setModalOpened] = useState<boolean>(false);

    const { register, handleSubmit } = useForm<Machine>({
        defaultValues: machine ?? {},
    });

    const save = (m: Machine) => {
        if (machine && machine['@id'] && machine['@id'].length > 0) {
            m['@id'] = machine['@id'];
            sshelter.updateMachine(m);
        } else {
            sshelter.createMachine(m);
        }
    };

    const deleteAction = async () => {
        if (!machine) {
            return;
        }

        await sshelter.deleteMachine(machine);
        await setModalOpened(false);
    }

    return <Box mt={10} ml={2}>
        <form onSubmit={handleSubmit(save)} style={{ display: 'flex', flexDirection: 'column' }}>
            <TextField style={styles} label="Machine name" variant="outlined" required {...register('name')} />
            <TextField style={styles} label="Short name" variant="outlined" {...register('shortName')} />
            <TextField style={styles} label="Hostname / IP" variant="outlined" required {...register('hostname')} />
            <TextField style={styles} label="Port" variant="outlined" type="number" required {...register('port')} />
            <TextField style={styles} label="Username" variant="outlined" {...register('username')} />
            <TextField style={styles} label="Other settings" variant="outlined" multiline minRows={5} {...register('otherSettings')} />

            {
                machine &&
                <Button variant="outlined" color="error" style={styles} onClick={() => setModalOpened(true)}>Delete</Button>
            }
            <Button variant="outlined" style={styles} type="submit">Save</Button>
        </form>

        <Modal open={modalOpened} onClose={() => setModalOpened(false)}>
            <Paper sx={modalStyle}>
                <Typography id="modal-modal-title" variant="h6" component="h2">
                    Deleting {machine?.name}
                </Typography>
                <Typography id="modal-modal-description" sx={{ mt: 2 }}>
                    Are you sure you want to remove this machine ?
                </Typography>

                <Box style={{display: 'flex', justifyContent: 'end', marginTop: '1em'}}>
                    <Button onClick={() => setModalOpened(false)}>Cancel</Button>
                    <Button onClick={() => deleteAction()} color="error">Delete</Button>
                </Box>
            </Paper>
        </Modal>
    </Box>
}