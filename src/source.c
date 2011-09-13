#include <stdlib.h>
#include <stdio.h>
#include <pthread.h>
#include <iostream>

double result = 0;
pthread_mutex_t mutex;


void* thread(void * arg)
{	
	long i = 0;	
	while(1)
	{
		pthread_mutex_lock(&mutex);
		for (int j = 0; j < 1000; j++)
		{		
			result += 1.0/(++i);
		}
		pthread_mutex_unlock(&mutex);
	}
}

int main()
{
	pthread_mutex_init(&mutex,0);
	pthread_t id;
	pthread_create(&id, NULL ,thread, NULL );
	double tmp = result;
	while ( true )
	{
		pthread_mutex_lock(&mutex);		
		if (tmp != result)
		{
			std::cout << result << "\n";
			tmp = result;
		}
		pthread_mutex_unlock(&mutex);
	}
	return 0;		
}

