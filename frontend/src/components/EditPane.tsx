import { Box, Button } from "@mui/material"
import { useForm } from "react-hook-form";
import { useMachineEditor } from "../hooks/MachineEditionProvider";
import { Machine } from "../models/Machine"
import CustomTextfield from "./CustomTextfield";

const styles = {
    marginTop: '.5em',
    marginBottom: '.25em',
};

export default function EditPane() {
    const editor = useMachineEditor();

    const { control, handleSubmit } = useForm<Machine>({
        defaultValues: editor.machine ?? {},
    });

    const save = (m: Machine) => {
        if (editor.machine && editor.machine['@id'] && editor.machine['@id'].length > 0) {
            m['@id'] = editor.machine['@id'];
        }

        editor.save();
    };

    return <Box mt={10} ml={2}>
        <form onSubmit={handleSubmit(save)} style={{ display: 'flex', flexDirection: 'column' }}>
            <CustomTextfield name="name" control={control} label="Machine name" defaultValue={editor.machine?.name} />
            <CustomTextfield name="shortName" control={control} label="Short name" defaultValue={editor.machine?.shortName} />
            <CustomTextfield name="hostname" control={control} label="Hostname / IP" defaultValue={editor.machine?.hostname} />
            <CustomTextfield name="port" control={control} label="Port" type="number" defaultValue={editor.machine?.port} />
            <CustomTextfield name="username" control={control} label="Username" defaultValue={editor.machine?.username} />
            <CustomTextfield name="otherSettings" control={control} label="Other settings" defaultValue={editor.machine?.otherSettings} multiline minRows={5} />

            {
                editor.machine &&
                <Button variant="outlined" color="error" style={styles} onClick={() => editor.remove()}>Delete</Button>
            }

            <Button variant="outlined" style={styles} type="submit">Save</Button>
        </form>
    </Box>
}