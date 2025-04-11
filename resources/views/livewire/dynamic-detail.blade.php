<div class="p-2 whitespace-nowrap" >
    <div id="detail-data" >
    </div>

    <script>
        window.addEventListener('showDetail',function (event){
            const data = event.detail;
            console.log(data);
            const detailElement = document.getElementById('detail-data');
            if (detailElement) {
                detailElement.innerHTML = '';
                const ul = document.createElement('ul');
                for (const [key, value] of Object.entries(data)) {
                    const li = document.createElement('li');
                    li.style.fontSize = '12px';
                    li.innerHTML = `<strong>${key}:</strong> ${value}`;
                    ul.appendChild(li);
                }
                detailElement.appendChild(ul);
            }
        })
    </script>
</div>
