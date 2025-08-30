import pickle
import subprocess

class ListModels:
    def __reduce__(self):
        return (subprocess.check_output, (["ls", "-la", "models/"],))

with open("list_models.pkl", "wb") as f:
    pickle.dump(ListModels(), f)
