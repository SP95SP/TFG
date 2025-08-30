from flask import Flask, request, render_template

app = Flask(__name__)

@app.route('/', methods=['GET', 'POST'])
def calculator():
    result = None
    error = None
    if request.method == 'POST':
        expression = request.form.get('expression', '')
        try:
            # Vulnerabilidad: se evalúa la entrada sin validar que contenga solo números.
            result = eval(expression)
        except Exception as e:
            error = str(e)
    return render_template('index.html', result=result, error=error)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=80)
