from flask import Flask, request, jsonify
import mysql.connector

app = Flask(__name__)

def connect_db():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",  # your actual DB password
        database="drf_database"
    )

@app.route('/save-measurement', methods=['POST'])
def save_measurement():
    data = request.get_json()
    name = data.get('name')
    length = data.get('length')
    vamp = data.get('vamp')
    instep = data.get('instep')

    if not all([name, length, vamp, instep]):
        return jsonify({'error': 'Missing fields'}), 400

    try:
        conn = connect_db()
        cursor = conn.cursor()
        cursor.execute("""
            INSERT INTO measurements (name, length, vamp, instep)
            VALUES (%s, %s, %s, %s)
        """, (name, length, vamp, instep))
        conn.commit()
        return jsonify({'success': True})
    except Exception as e:
        return jsonify({'error': str(e)}), 500
    finally:
        cursor.close()
        conn.close()

if __name__ == '__main__':
    app.run(debug=True)
