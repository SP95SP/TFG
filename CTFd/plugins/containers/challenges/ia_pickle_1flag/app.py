from flask import Flask, request, render_template
import os
import pickle

app = Flask(__name__)

MODEL_PATH = "models/model.pkl"

@app.route('/')
def home():
    return render_template("form.html")

@app.route('/upload', methods=['POST'])
def upload():
    file = request.files.get('model')
    if file:
        os.makedirs("models", exist_ok=True)
        file.save(MODEL_PATH)
        return "Modelo subido exitosamente"
    return "Error: No se subio ningun archivo", 400

@app.route('/predict')
def predict():
    try:
        with open(MODEL_PATH, "rb") as f:
            model = pickle.load(f)
        result = str(model)  # Aquí se ejecutaría el payload malicioso si lo hay
        return f"Prediccion: {result}"
    except Exception as e:
        return f"Error al procesar el modelo: {str(e)}"

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
