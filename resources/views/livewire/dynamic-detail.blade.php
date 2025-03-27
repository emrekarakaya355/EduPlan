<div class="p-4 whitespace-nowrap" >
    <div id="detail-data" class="overflow-x-auto" >
    </div>

    <script>
        window.addEventListener('showDetail',function (event){
            const data = event.detail;  // Event'ten gelen veriye erişiyoruz
            console.log(data);
            const detailElement = document.getElementById('detail-data');
            if (detailElement) {
                // HTML içeriğini temizliyoruz
                detailElement.innerHTML = '';
                // Gelen verileri key-value formatında listeye dönüştürüp HTML'ye ekliyoruz
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
