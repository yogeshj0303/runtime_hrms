var riskEmployees = {
    no: [
        {
            initials: 'AP',
            name: 'AMIT PATEL',
            code: '821',
            location: 'INDORE WORKSHOP JAWA',
            dept: 'Service',
            updated: '30-06-2026',
            score: '4'
        },
        {
            initials: 'RK',
            name: 'RAHUL KUMAR',
            code: '824',
            location: 'INDORE SHOWROOM JAWA',
            dept: 'Sales',
            updated: '30-06-2026',
            score: '3'
        },
        {
            initials: 'SV',
            name: 'SACHIN VERMA',
            code: '827',
            location: 'INDORE WORKSHOP JAWA',
            dept: 'Spare Parts',
            updated: '30-06-2026',
            score: '2'
        },
        {
            initials: 'NS',
            name: 'NEHA SHARMA',
            code: '829',
            location: 'INDORE SHOWROOM JAWA',
            dept: 'HR',
            updated: '30-06-2026',
            score: '1'
        }
    ],

    moderate: [
        {
            initials: 'AY',
            name: 'AAYUSHI YADAV',
            code: '812',
            location: 'INDORE WORKSHOP JAWA',
            dept: 'Service',
            updated: '30-06-2026',
            score: '14'
        },
        {
            initials: 'AS',
            name: 'AJAY SARVAA',
            code: '800',
            location: 'INDORE WORKSHOP JAWA',
            dept: 'Service',
            updated: '30-06-2026',
            score: '13'
        },
        {
            initials: 'CB',
            name: 'CHETAN BAI BERWAD',
            code: '810',
            location: 'INDORE WORKSHOP JAWA',
            dept: 'Service',
            updated: '30-06-2026',
            score: '12'
        }
    ],

    high: [
        {
            initials: 'HR',
            name: 'HARSHIT ROSHAN',
            code: '914',
            location: 'INDORE SHOWROOM JAWA',
            dept: 'Sales',
            updated: '30-06-2026',
            score: '1'
        }
    ]
};

function getRiskLabel(type){
    if(type === 'no'){
        return 'No Risk';
    }

    if(type === 'moderate'){
        return 'Moderate Risk';
    }

    if(type === 'high'){
        return 'High Risk';
    }

    return 'None';
}

function getScoreClass(type){
    if(type === 'no'){
        return 'score-no';
    }

    if(type === 'moderate'){
        return 'score-moderate';
    }

    if(type === 'high'){
        return 'score-high';
    }

    return 'score-no';
}

function setEmptyTable(){
    var tbody = document.getElementById('riskTableBody');

    if(!tbody){
        return;
    }

    tbody.innerHTML = '<tr class="empty-row"><td colspan="5"></td></tr>';
}

function renderRiskRows(type){
    var tbody = document.getElementById('riskTableBody');
    var viewingText = document.getElementById('riskViewingText');

    if(!tbody){
        return;
    }

    if(viewingText){
        viewingText.textContent = getRiskLabel(type);
    }

    var employees = riskEmployees[type] || [];

    if(employees.length === 0){
        setEmptyTable();
        return;
    }

    var rows = '';

    employees.forEach(function(emp){
        rows += `
            <tr>
                <td>
                    <div class="employee-cell">
                        <span class="employee-avatar">${emp.initials}</span>
                        <div>
                            <a href="javascript:void(0);">${emp.name}</a>
                            <p>${emp.code}</p>
                        </div>
                    </div>
                </td>

                <td>
                    <p class="dep-line"><i class="ri-map-pin-user-fill pink"></i>${emp.location}</p>
                    <p class="dep-line"><i class="ri-building-4-line blue"></i>${emp.dept}</p>
                </td>

                <td>${emp.updated}</td>

                <td>
                    <span class="score-badge ${getScoreClass(type)}">${emp.score}</span>
                </td>

                <td>
                    <button type="button" class="action-btn">
                        <i class="ri-eye-line"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = rows;
}

document.addEventListener('DOMContentLoaded', function(){

    setEmptyTable();

    var riskCards = document.querySelectorAll('.risk-click');

    riskCards.forEach(function(card){
        card.addEventListener('click', function(){
            var riskType = this.getAttribute('data-risk');
            renderRiskRows(riskType);
        });
    });

});