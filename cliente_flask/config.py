import os
from dotenv import load_dotenv

load_dotenv()

class Config:
    SECRET_KEY = os.getenv('SECRET_KEY', 'sage_secret_key')
    API_BASE_URL = os.getenv('API_URL', 'http://localhost:8000')
    DEBUG = True