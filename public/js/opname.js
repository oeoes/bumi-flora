'use strict';

const fromField = document.querySelector('#from');
const toField = document.querySelector('#to');
const categoryField = document.querySelector('#category');
const cabinetField = document.querySelector('#cabinet');
const deptField = document.querySelector('#dept');
const dataOpnameContainer = document.querySelector('#data-opname');
const btnRunFilter = document.querySelector('#run-opname');

const createNewElement = (el_tr, el_td, attr, align_column = false) => {      
    
    if (align_column) {
        let tr = document.createElement(el_tr);   
        for (let attribute of attr) {
            let td = document.createElement(el_td);
            for (let key in attribute) {                
                if (key !== 'message') {
                    td.setAttribute(key, attribute[key]);
                } else {
                    td.textContent = attribute[key];
                }                
            }
            tr.appendChild(td);            
        }
        return tr
    } else {
        let tr = document.createElement(el_tr);
        let td = document.createElement(el_td);
        for (let i in attr) {
            for (let key in attr[i]) {
                if (key !== 'message') {
                    td.setAttribute(key, attr[i][key]);
                } else {
                    td.textContent = attr[i][key];
                }
            }
        }
        tr.appendChild(td);
        return tr
    }
}

const filter_opname = () => {
    btnRunFilter.textContent = "Running...";

    axios.get('/app/storages/item/opname/filter', {
        params: {
            from: fromField.value,
            to: toField.value,
            category: categoryField.value,
            cabinet: cabinetField.value,
            dept: deptField.value,
        }
    }).then(function (response) {
        if (response.data.data.length < 1) {
            dataOpnameContainer.innerHTML = '';
            let attributes = [{ 'colspan': '11', 'align': 'center', 'message': 'Data kosong.' }];
            let tr = createNewElement('tr', 'td', attributes);
            dataOpnameContainer.appendChild(tr);
        } else {
            localStorage.removeItem('data_input');
            let data_input = [];
            dataOpnameContainer.innerHTML = '';
            response.data.data.forEach(element => {
                let acc = ((parseInt(element.balance) + parseInt(Number(element.amount_in))) - parseInt(Number(element.amount_out)))
                $('#data-opname').append(`<tr><td>${element.name}</td><td>${element.unit}</td><td>${element.category}</td><td>${element.dept}</td><td>${element.cabinet}</td><td>${element.balance}</td><td>${Number(element.amount_in)}</td><td>${Number(element.amount_out)}</td><td><input id="record_${element.id}" type="number" class="form-control text-center" min="0" value="${acc}" readonly/></td><td><input id="${element.id}" type="number" class="form-control text-center real_amount" name="real_amount" min="0" value="${acc}"/></td><td><span id="ratio_${element.id}">0</span></td></tr>`)

                data_input.push(acc);
            });
            $('#data-opname').append('<tr><td colspan="10"></td><td><button onclick="export_opname()" class="btn btn-sm btn-outline-primary">Export</button></td></tr>')
            
            localStorage.setItem('data_input', JSON.stringify(data_input));
        }
    }).finally(function () {
        setTimeout(() => {
            btnRunFilter.textContent = 'Run';
        }, 700);
    })
}

const export_opname = () => {
    axios({
        method: 'post',
        url: '/app/storages/item/opname/export',
        responseType: 'arraybuffer',
        data: {
            from: fromField.value,
            to: toField.value,
            category: categoryField.value,
            cabinet: cabinetField.value,
            dept: deptField.value,
            data_input: JSON.parse(localStorage.getItem('data_input')),
        }
    }).then(function (response) {
        let blob = new Blob([response.data], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        });   
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.download = 'stock-opname-export.xlsx';
        document.body.appendChild(link);
        link.click();
    }).catch(function (error) {
        console.log(error);        
    })
}

window.onload = () => {
let attributes = [{ 'colspan': '11', 'align': 'center', 'message': 'Tidak ada data yang ditampilkan.' }];
    let tr = createNewElement('tr', 'td', attributes);
    dataOpnameContainer.appendChild(tr);

    $(document).on('keyup', "input[name='real_amount']", function () {
        localStorage.removeItem('data_input');
        let data_input = [];

        let inputAmount = document.querySelectorAll('.real_amount');
        let id = $(this).attr('id');
        let calculate_ratio = parseInt($(`#${id}`).val()) - parseInt($(`#record_${id}`).val());
        
        $(`#ratio_${id}`).text(calculate_ratio);

        for (let i = 0; i < inputAmount.length; i++) {
            data_input.push(parseInt(inputAmount[i].value));
        }

        localStorage.setItem('data_input', JSON.stringify(data_input));
        console.log(typeof localStorage.getItem('data_input'));
        
    });
    

}