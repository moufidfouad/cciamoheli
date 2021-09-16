import { Controller } from 'stimulus';
import axios from 'axios';

export default class extends Controller {
    connect() {
        this.element.addEventListener('submit',(submit) => {
            this.onSubmit(submit);
        });
    }

    onSubmit(event){
        event.preventDefault();
        const errorWrapper = this.element.querySelector('.error-wrapper');
        const ulNode = errorWrapper.querySelector('ul');
        ulNode.innerHTML = '';
        const formName = this.element.getAttribute('name');
        const buttonSubmit = this.element.querySelector('#' + formName + '_submit');

        buttonSubmit.style.display = "none";
        const formData = new FormData(this.element);

        const response = axios.post(this.element.action,new URLSearchParams(formData),{
            headers: {
                'X-Requested-With':'XMLHttpRequest'
            }
        });
        response.then((data) => { 
            buttonSubmit.style.display = "block";
            if(data.status === 203){
                for(const [key, value] of Object.entries(data.data.error)) {
                    ulNode.innerHTML +=  `<li>${value}</li>`;
                }
            }else{
                this.element.reset();
            }
        }).catch(error => { 
            buttonSubmit.style.display = "block";
            console.log(error);
        });
    }
}
