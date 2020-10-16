import useSWR from 'swr';
import ApiService from '../services/ApiService';

export function useFetch(getUrl) {
  const { data, error, mutate } = useSWR(getUrl, async url => {
    const response = await ApiService.get(url);
    return response;
  });

  return { data, error, mutate };
}
