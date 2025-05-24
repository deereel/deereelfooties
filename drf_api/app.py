from flask import Flask, request, jsonify
import mysql.connector

app = Flask(__name__)

db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="drf_database"
)

@app.route('/submit-measurement', methods=['POST'])
def submit_measurement():
    data = request.json
    name = data.get("name")
    length = data.get("length")
    vamp = data.get("vamp")
    instep = data.get("instep")

    cursor = db.cursor()
    cursor.execute("INSERT INTO measurements (name, length, vamp, instep) VALUES (%s, %s, %s, %s)",
                   (name, length, vamp, instep))
    db.commit()
    return jsonify({"success": True})

if __name__ == '__main__':
    app.run(debug=True)
