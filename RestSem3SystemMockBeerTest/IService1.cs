using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.ServiceModel;
using System.ServiceModel.Web;
using System.Text;
using System.Web.Script.Services;
using RestSem3SystemMockBeerTest.Models;

namespace RestSem3SystemMockBeerTest
{
    [ServiceContract]
    public interface IService1
    {
        [WebInvoke(UriTemplate = "beers/fridge", Method="GET", ResponseFormat = WebMessageFormat.Json)]
        [OperationContract]
        string GetFridgeValue();

        [WebInvoke(UriTemplate = "beers/total", Method = "GET", ResponseFormat = WebMessageFormat.Json)]
        [OperationContract]
        string GetTotalValue();

        [WebInvoke(UriTemplate = "beers/update/{token}/{value}", Method = "GET", ResponseFormat = WebMessageFormat.Json)]
        [OperationContract]
        int UpdateFridge(string token, string value);

        [WebInvoke(UriTemplate = "profile/login/{token}",
            Method = "POST",
            RequestFormat = WebMessageFormat.Json,
            ResponseFormat = WebMessageFormat.Json,
            BodyStyle = WebMessageBodyStyle.Bare)]
        [OperationContract]
        Profile Login(string token, Profile profile);
        
        [WebInvoke(UriTemplate = "profile/create/{token}", 
            Method = "POST",
            RequestFormat = WebMessageFormat.Json,
            ResponseFormat = WebMessageFormat.Json,
            BodyStyle = WebMessageBodyStyle.Bare)]
        [OperationContract]
        string CreateProfile(string token, Profile profile);

        [WebInvoke(UriTemplate = "profile/read/{token}", Method = "GET", ResponseFormat = WebMessageFormat.Json)]
        [OperationContract]
        IList<Profile> GetAllProfiles(string token);

        [WebInvoke(UriTemplate = "profile/delete/{token}/{id}", Method = "GET", ResponseFormat = WebMessageFormat.Json)]
        [OperationContract]
        int DeleteProfile(string token, string id);

        [WebInvoke(UriTemplate = "settings/update/fridgemax/{token}/{value}", Method = "GET", ResponseFormat = WebMessageFormat.Json)]
        [OperationContract]
        int UpdateFridgeMax(string token, string value);

        [WebInvoke(UriTemplate = "settings/update/fridgemin/{token}/{value}", Method = "GET", ResponseFormat = WebMessageFormat.Json)]
        [OperationContract]
        int UpdateFridgeMin(string token, string value);

        [WebInvoke(UriTemplate = "settings/update/notifymin/{token}/{value}", Method = "GET", ResponseFormat = WebMessageFormat.Json)]
        [OperationContract]
        int UpdateNotifyMin(string token, string value);

        [WebInvoke(UriTemplate = "beers/read", Method = "GET", ResponseFormat = WebMessageFormat.Json)]
        [OperationContract]
        IList<Beer> GetAllBeers();

        [WebInvoke(UriTemplate = "profile/update/password/{token}", 
            Method = "PUT",
            RequestFormat = WebMessageFormat.Json,
            ResponseFormat = WebMessageFormat.Json,
            BodyStyle = WebMessageBodyStyle.Bare)]
        [OperationContract]
        int UpdatePassword(string token, Profile profile);

        [WebInvoke(UriTemplate = "settings/read/fridgemin", Method = "GET", ResponseFormat = WebMessageFormat.Json)]
        [OperationContract]
        int GetFridgeMin();

        [WebInvoke(UriTemplate = "settings/read/fridgemax", Method = "GET", ResponseFormat = WebMessageFormat.Json)]
        [OperationContract]
        int GetFridgeMax();

        [WebInvoke(UriTemplate = "settings/read/notifymin", Method = "GET", ResponseFormat = WebMessageFormat.Json)]
        [OperationContract]
        int GetNotifyMin();

        [WebInvoke(UriTemplate = "profile/read/role/{token}/{id}", Method = "GET", ResponseFormat = WebMessageFormat.Json)]
        [OperationContract]
        int GetRole(string token, string id);

        [WebInvoke(UriTemplate = "beers/update/total/{token}/{value}", Method = "GET", ResponseFormat = WebMessageFormat.Json)]
        [OperationContract]
        int UpdateStorage(string token, string value);
    }
}
