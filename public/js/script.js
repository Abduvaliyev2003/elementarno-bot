const searchInput = document.getElementById('search-input');
const searchResults = document.getElementById('search-results');


document.getElementById('menu-button').addEventListener('click', function() {
    var wordSection = document.getElementById('word-section');
    if (wordSection.style.display === 'none' || wordSection.style.display === '') {
        wordSection.style.display = 'grid';
    } else {
        wordSection.style.display = 'none';
    }
});

function playAudio(id) {
    var audio = document.getElementById(`audio-${id}`);
    if (audio) {
        audio.play();
    } else {
        console.error('Audio element not found');
    }

  }

searchInput.addEventListener('input', function() {
      const query = this.value.trim();

      if (query.length === 0) {
          searchResults.innerHTML = '';
          return;
      }

      fetch(`/search/result?query=${query}`)
          .then(response => response.json())
          .then(data => {
              searchResults.innerHTML = '';

              data.forEach(item => {
                const wordContainer = document.createElement('div');
                wordContainer.classList.add('word-container');

                const wordItem = document.createElement('a');
                wordItem.classList.add('word-item');
                wordItem.href = `/word/${item.id}`; // Replace with your dynamic URL
                wordItem.setAttribute('target', '_blank'); // Optional: Opens link in a new tab

                const wordImage = document.createElement('img');
                wordImage.classList.add('word-image');
                wordImage.src = '/img/Rectangle 91.png';
                wordImage.alt = 'Rasm';

                const wordText = document.createElement('div');
                wordText.classList.add('word-text');

                const wordTitle = document.createElement('h1');
                wordTitle.classList.add('word-title');
                wordTitle.textContent = item.name; // Adjust based on your data structure

                const transcription = document.createElement('div');
                transcription.classList.add('transcription');

                const transcriptionText = document.createElement('h2');
                transcriptionText.textContent = item.translations['uz']; // Adjust based on your data structure

                // Append elements to construct the structure
                transcription.appendChild(transcriptionText);
                wordText.appendChild(wordTitle);
                wordText.appendChild(transcription);
                wordItem.appendChild(wordImage);
                wordItem.appendChild(wordText);
                wordContainer.appendChild(wordItem);

                searchResults.appendChild(wordContainer);
            });
          })
          .catch(error => console.error('Error:', error));
  });
