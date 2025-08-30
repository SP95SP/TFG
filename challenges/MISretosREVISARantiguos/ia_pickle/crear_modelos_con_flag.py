# crear_modelos_con_flag.py
import pickle
import os

# Cargar la flag2
with open("_flag2.txt", "r") as f:
    flag2 = f.read().strip()

# Modelos falsos (relleno)
nombres = ["ana_model", "carla_model", "juan_model", "pepe_model"]

for nombre in nombres:
    with open(f"models/{nombre}.pkl", "wb") as f:
        pickle.dump({"modelo": "dummy"}, f)

# Modelo admin con la flag embebida (como string plano)
with open("models/admin_model.pkl", "wb") as f:
    pickle.dump(flag2, f)
