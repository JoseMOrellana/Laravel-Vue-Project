from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import NoAlertPresentException, NoSuchElementException
from selenium.common.exceptions import TimeoutException

from time import sleep
import sys 


# Función principal

def main():
    BASE_URL = 'http://127.0.0.1:8000'
    # Opciones del navegador 
    # options = webdriver.FirefoxOptions()
    options = webdriver.ChromeOptions()
    options.binary_location = r'C:\Program Files (x86)\BraveSoftware\Brave-Browser\Application\brave.exe'

    # driver = webdriver.Firefox()
    driver = webdriver.Chrome('chromedriver.exe', chrome_options=options)
    driver.implicitly_wait(30)
    driver.maximize_window()
    wait = WebDriverWait(driver, 10)

    driver.get(BASE_URL)

    login_link = driver.find_elements_by_tag_name('a')[0]
    login_link.click()


    email = 'andres@gmail.com'
    password = 'password'

    input_email = driver.find_element_by_id('email')
    input_email.send_keys(email)
    input_password = driver.find_element_by_id('password')
    input_password.send_keys(password)

    submit_button = driver.find_element_by_css_selector('button.btn')
    submit_button.click()

    driver.get(f'{BASE_URL}/users')

    add_user_button = driver.find_element_by_css_selector('#app > div > div > div > div.container > div.row.mt-5 > div > div > div.card-header > div > button')
    add_user_button.click()

    input_nombre = driver.find_element_by_css_selector('#addNewModal > div > div > form > div.modal-body > div:nth-child(1) > input')
    input_nombre.send_keys('Andress')

    input_apellido = driver.find_element_by_css_selector('#addNewModal > div > div > form > div.modal-body > div:nth-child(2) > input')
    input_apellido.send_keys('Bravoo')

    input_correo = driver.find_element_by_css_selector('#addNewModal > div > div > form > div.modal-body > div:nth-child(3) > input')
    input_correo.clear()
    at = u'\u0040'
    sleep(1)
    input_correo.send_keys(f'andres')
    sleep(1)
    input_correo.send_keys(f'{at}andres.com')
    sleep(1)

    input_contraseña = driver.find_element_by_css_selector('#addNewModal > div > div > form > div.modal-body > div:nth-child(4) > input')
    input_contraseña.send_keys('password1234')

    input_contraseña2 = driver.find_element_by_css_selector('#addNewModal > div > div > form > div.modal-body > div:nth-child(5) > input')
    input_contraseña2.send_keys('password1234')

    input_cedula = driver.find_element_by_css_selector('#addNewModal > div > div > form > div.modal-body > div:nth-child(6) > input')
    input_cedula.clear()
    input_cedula.send_keys('24587895')
    
    input_telf = driver.find_element_by_css_selector('#addNewModal > div > div > form > div.modal-body > div:nth-child(7) > input')
    input_telf.clear()
    input_telf.send_keys('04241234567')
    
    stop_ask(driver)

    driver.find_element_by_css_selector('#addNewModal > div > div > form > div.modal-footer > button.btn.btn-success').click()

    stop_ask(driver)

    sign_out = driver.find_element_by_css_selector('#app > aside > div > nav > ul > li:nth-child(4) > a')
    sleep(4)
    print('[!] La ejecución del programa finalizó con exito')

def stop_ask(driver):
    ask = str(input('Desea continuar? [S/n]: '))
    while ask.lower() == 's' or ask.lower() == 'si' or ask.lower() != 'n':
        return

    driver.close()
    sys.exit(0)





if __name__ == '__main__':
    main()