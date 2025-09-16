// Proyecto_aula/config/supabaseClient.js

import { createClient } 
from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm';

const supabaseUrl = 'https://vmblneiddobhucxvkovd.supabase.co';
const supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZtYmxuZWlkZG9iaHVjeHZrb3ZkIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTc5NTMwNjAsImV4cCI6MjA3MzUyOTA2MH0.bG5Iyq2rN6TwQ-wfRaPJ5S2tVAOdUukBRolxJ3n_h18';

export const supabase = createClient(supabaseUrl, supabaseKey);