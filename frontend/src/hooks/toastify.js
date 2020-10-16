import { toast } from 'react-toastify';

const success = message => {
  toast.success(message);
};

const error = message => {
  toast.error(message);
};

export default function useToastify() {
  return { success, error };
}
