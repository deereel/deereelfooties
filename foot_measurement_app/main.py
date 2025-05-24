from kivy.app import App
from kivy.uix.boxlayout import BoxLayout
from kivy.uix.label import Label
from kivy.uix.textinput import TextInput
from kivy.uix.button import Button
import requests

class MeasurementForm(BoxLayout):
    def __init__(self, **kwargs):
        super().__init__(orientation='vertical', padding=10, spacing=10, **kwargs)
        
        self.add_widget(Label(text="Client Name"))
        self.name_input = TextInput(multiline=False)
        self.add_widget(self.name_input)

        self.add_widget(Label(text="Foot Length (cm)"))
        self.length_input = TextInput(multiline=False)
        self.add_widget(self.length_input)

        self.add_widget(Label(text="Vamp (cm)"))
        self.vamp_input = TextInput(multiline=False)
        self.add_widget(self.vamp_input)

        self.add_widget(Label(text="Instep (cm)"))
        self.instep_input = TextInput(multiline=False)
        self.add_widget(self.instep_input)

        self.submit_btn = Button(text="Submit Measurement", on_press=self.submit)
        self.add_widget(self.submit_btn)

        self.message = Label(text="")
        self.add_widget(self.message)

    def submit(self, instance):
        payload = {
            "name": self.name_input.text,
            "length": self.length_input.text,
            "vamp": self.vamp_input.text,
            "instep": self.instep_input.text
        }
        try:
            res = requests.post("http://127.0.0.1:5000/submit-measurement", json=payload)
            if res.status_code == 200:
                self.message.text = "✅ Measurement submitted!"
            else:
                self.message.text = "❌ Submission failed."
        except:
            self.message.text = "❌ Server not reachable."

class FootApp(App):
    def build(self):
        return MeasurementForm()

if __name__ == "__main__":
    FootApp().run()
