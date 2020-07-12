
class Validation
{  
    constructor(form)
    {
        this.form = form;
        this.elem;
        this.passColor = '#71ce71';
        this.failColor = 'red';
    }
    get elements ()
    {
        return this.form.elements
    }
    check_form()
    {
        let elem;
        for(elem of this.form)
        {
            if(elem.classList.contains('validate'))
            {
                this.elem = elem
                this.requirements = JSON.parse(elem.dataset.validate);
                for(const k in this.requirements)
                {                        
                    switch(k)
                    {
                        case "required":
                            this.required()
                        break ;
                        case "matches":
                            this.matches(this.requirements[k])
                        break ;
                    }
                }
            }
        }
    }
    check_elem()
    {

    }
    
    required()
    {
        let elem_value = this.elem.value;
        if(elem_value != "")
        {
            this.validation_pass('ok')
        } 
        else 
        {
            this.validation_fail('This field is required!');
        }
    }
    matches(matches_elem)
    {
        // check to see if value of field matches the value of another specified field
        matches_elem = document.querySelector(`#${matches_elem}`);
        let match_value = matches_elem.value;
        //debugger;
        let match_label = matches_elem.parentElement.lastElementChild.closest('label').textContent;
        let elem_value = this.elem.value;
        if(elem_value == match_value)
        {
            this.validation_pass('OK')
        } 
        else 
        {
            this.validation_fail(`This should match the ${match_label} field`)
        }
    }
    validation_pass(message)
    {
        this.valid = (typeof this.valid != 'undefined' && this.value != false?true:false);
        this.insert_message(this.passColor,message)
        console.log(this.elem.id+": Pass")
    }
    validation_fail(message)
    {
        this.valid = false;
        this.insert_message(this.failColor,message);
        console.log(this.elem.id+": Fail")
    }
    insert_message(color,message){
        this.elem.style.borderColor = color;
        let message_span = document.createElement('span');
        message_span.addClass = '__validation__result__messaage'
        message_span.style.display = 'block'
        message_span.style.color = color;
        message_span.innerHTML = message

        this.elem.parentNode.insertBefore(message_span, this.elem.nextSibling);
    }
    reset_form(){

    }
}
export default Validation;